<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Constants;
use AppBundle\Util;

/**
 * AppEntityRepository
 */
abstract class AppRepository extends EntityRepository
{
    /**
     * 検索フォームからDQLを生成して検索結果を返す関数
     * 検索ワードの空白区切りでAND検索、あいまい検索、大文字小文字は区別しない。
     * リレーションの検索が必要な場合はオーバーライドして対応する。
     *
     * 単体カラムは AND 検索
     * 複数カラムは OR 検索
     *
     * @param Form $from
     * @param array $multiple 複数カラム検索オプション
     * @param array $orderBy 並び替え検索オプション
     *
     * @return Entities
     */
    public function findByForm($form, $multiple = array(), $orderBy = array())
    {
        $entity = $form->getData();
        $formDataArray = $this->toArrayByForm($form);

        // クエリ生成
        $entities = array();
        if ($formDataArray) {
            // WHERE句
            $sql = '';
            $param = array();
            $qb = $this->createQueryBuilder('t');

            // 複数カラムのSQLを生成
            foreach ($multiple as $form => $targets) {
                if (array_key_exists($form, $formDataArray)) {
                    $textValues = explode(' ', $formDataArray[$form]);

                    if ($textValues && $targets) {
                        foreach ($targets as $target) {
                            $temp = '';
                            for ($i = 0, $cnt = count($textValues); $i < $cnt; $i++) {
                                $temp .= "(UPPER(t.$target) LIKE :".$form.$i.")" . ' AND ';
                                $param += array($form.$i => '%'.strtoupper($textValues[$i]).'%');
                            }
                            $temp = rtrim($temp, ' AND ');
                            $sql .= '(' . $temp . ') OR ';
                        }
                        $sql = rtrim($sql, ' OR ');
                        $sql = '(' . $sql . ') AND ';
                    }

                    // 単体カラムで計算させないため除外する
                    unset($formDataArray[$form]);
                }
            }

            // 単体カラムのSQLを生成
            foreach ($formDataArray as $key => $textValue) {
                $textValues = explode(' ', $textValue);

                for ($i = 0, $cnt = count($textValues); $i < $cnt; $i++) {
                    $sql .= "(UPPER(t.$key) LIKE :".$key.$i.")" . " AND ";
                    $param += array($key.$i => '%'.strtoupper($textValues[$i]).'%');
                }
            }

            $sql = rtrim($sql, ' AND ');

            if ($sql) {
                $qb->andWhere($sql);
                $qb->setParameters($param);
            }

            // ORDER BY句
            $sql = $this->getOrderByPaginator($orderBy);

            if ($sql) {
                $qb->add('orderBy', $sql);
            }

            $entities = $qb->getQuery()->getResult();
        }

        return $entities;
    }

    /**
     * フォームの入力データを配列に変換
     *
     * @return Array
     */
    public static function toArrayByForm($form)
    {
        global $kernel;
        if ($kernel instanceOf \AppCache) {
            $kernel = $kernel->getKernel();
        }
        $container = $kernel->getContainer();

        $request = $container->get('request');

        // 入力された検索条件取得
        $formDataArray = array();
        if ($request->getMethod() === 'POST') {
            $formDataArray = $request->request->get($form->getName());
            unset($formDataArray['_token']);
            unset($formDataArray['submit']);

            // 空要素チェック＆削除
            $formDataArray = Util::mightRemoveEmptyInArray($formDataArray);

            $request->getSession()->set(Constants::SESSION_FIND_KEYWORD, $formDataArray);
        } else {
            $formDataArray = $request->getSession()->get(Constants::SESSION_FIND_KEYWORD);
        }

        return $formDataArray;
    }

    /**
     * 検索用 ページャーから並び順を取得
     *
     */
    public static function getOrderByPaginator($orderBy, $table = 't')
    {
        global $kernel;
        if ($kernel instanceOf \AppCache) {
            $kernel = $kernel->getKernel();
        }
        $container = $kernel->getContainer();

        $request = $container->get('request');

        $sort = $request->query->get('sort', false);
        $direction = $request->query->get('direction', false);
        if ($sort && $direction) {
            $orderBy = array($sort => $direction);
        }

        $sql = '';
        foreach ($orderBy as $key => $value) {
            if (strpos($key, '.') === false) {
                $key = $table . '.' . $key;
            }
            $sql = $sql . "$key $value" . " , ";
        }
        $sql = rtrim($sql, " , ");

        return $sql;
    }

    /**
     * エンティティにパラメータをセットする関数
     *
     * Entity $parameters エンティティ
     * Array $parameters エンティティに渡すパラメータ（キー値はスネークケースorキャメルケース）
     * Array $exclude 除外リスト
     */
    public function setParameters($entity, Array $parameters, $exclude = array('id'))
    {
        $entityInfo = $this->getClassMetadata($this->_entityName);

        // フィールドタイプを取得
        $fieldMappings = $entityInfo->fieldMappings;
        $fieldTypes = array();
        foreach ($fieldMappings as $key => $field) {
            $fieldTypes[$field['fieldName']] = $field['type'];
        }

        // セッター生成
        $setterNames = array();
        foreach ($parameters as $columnName => $columnValue) {
            $setterNames[$columnName] = 'set'.Util::toPascalcase($columnName);
        }

        // エンティティ生成
        $em = $this->getEntityManager();

        // 型に応じてJSONデータをセット
        foreach ($parameters as $columnName => $columnValue) {
            $_columnName = Util::toCamelcase($columnName);
            $_columnValue = $columnValue;

            if (array_key_exists($_columnName, $fieldTypes)) {
                // 型変換
                if ($fieldTypes[$_columnName] == 'integer') {
                    $_columnValue = intval($columnValue);
                } elseif ($fieldTypes[$_columnName] == 'float') {
                    $_columnValue = floatval($columnValue);
                } elseif ($fieldTypes[$_columnName] == 'datetime') {
                    // UNIX時間 to 日時文字列 変換
                    $tempValue = Util::toDateString($columnValue, 'Y-m-d H:i:s');
                    $_columnValue = new \DateTime($tempValue);
                } elseif ($fieldTypes[$_columnName] == 'decimal') {
                    $_columnValue = floatval($columnValue);
                } elseif ($fieldTypes[$_columnName] == 'boolean') {
                    $_columnValue = (boolean)($columnValue);
                }

                // 除外
                if (in_array($_columnName, $exclude) === false) {
                    $entity->$setterNames[$columnName]($_columnValue);
                }
            }
        }

        return $entity;
    }

    /**
     * テストデータ投入用関数
     * 既存のエンティティを返す
     */
    public function getRandom()
    {
        $count = count($this->findAll());
        $entities = $this->createQueryBuilder('r')
            ->setFirstResult(rand(0, $count - 1))
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        foreach ($entities as $entity) {
            return $entity;
        }

        return 0;
    }

    /**
     * テストデータ投入用関数
     * 既存のidを返す
     */
    public function getRandomId()
    {
        $count = count($this->findAll());
        $entities = $this->createQueryBuilder('r')
            ->setFirstResult(rand(0, $count - 1))
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        foreach ($entities as $entity) {
            return $entity->getId();
        }

        return 0;
    }
}
