<?php

namespace AppBundle;

use Symfony\Component\Yaml\Parser;

/**
 * Util
 *
 * use AppBundle\Util;
 */
class Util
{
    // ログレベル
    const LEVEL_DEBUG     = "debug";        // デバッグレベル
    const LEVEL_INFO      = "info";         // 情報レベル
    const LEVEL_NOTICE    = "notice";       // 通知レベル
    const LEVEL_WARNING   = "warning";      // 警告レベル
    const LEVEL_ERROR     = "error";        // エラーレベル
    const LEVEL_CRITICAL  = "critical";     // 重大なエラーレベル
    const LEVEL_ALERT     = "alert";        // さらに重大なエラーレベル
    const LEVEL_EMERGENCY = "emergency";    // サーバが稼動できないほどの重大なエラーレベル

    /**
     * ログを出力する
     *
     * Usage
     * Util::setDebugLog('デバッグログメッセージ');
     * Util::setInfoLog('情報ログメッセージ');
     * Util::setNoticeLog('通知ログメッセージ');
     * Util::setWarningLog('警告ログメッセージ');
     * Util::setErrorLog('エラーログメッセージ');
     * Util::setCriticalLog('データ不整合ログメッセージ');
     * Util::setAlertLog('不正な操作ログメッセージ');
     * Util::setEmergencyLog('緊急エラーログメッセージ');
     *
     * @param message ログに出力する内容
     * @param level ログレベル
     */
    public static function setLog($message = '', $level = self::LEVEL_DEBUG)
    {
        global $kernel;
        $kernel->getContainer()->get('logger')->$level($message);
    }

    /**
     * "Debug" レベルのログを出力する
     *
     * @param message ログに出力する内容
     */
    public static function setDebugLog($message = '')
    {
        self::setLog($message, self::LEVEL_DEBUG);
    }

    /**
     * "Info" レベルのログを出力する
     *
     * @param message ログに出力する内容
     */
    public static function setInfoLog($message = '')
    {
        self::setLog($message, self::LEVEL_INFO);
    }

    /**
     * "Notice" レベルのログを出力する
     *
     * 通常の動作だが注意が必要な動作などの情報
     *
     * @param message ログに出力する内容
     */
    public static function setNoticeLog($message = '')
    {
        self::setLog($message, self::LEVEL_NOTICE);
    }

    /**
     * "Warning" レベルのログを出力する
     *
     * 入力チェックなどのユーザ操作ミス
     *
     * @param message ログに出力する内容
     */
    public static function setWarningLog($message = '')
    {
        self::setLog($message, self::LEVEL_WARNING);
    }

    /**
     * "Error" レベルのログを出力する
     *
     * 例外発生時
     *
     * @param message ログに出力する内容
     */
    public static function setErrorLog($message = '')
    {
        self::setLog($message, self::LEVEL_ERROR);
    }

    /**
     * "Critical" レベルのログを出力する
     *
     * データが不整合である時
     *
     * @param message ログに出力する内容
     */
    public static function setCriticalLog($message = '')
    {
        self::setLog($message, self::LEVEL_CRITICAL);
    }

    /**
     * "Alert" レベルのログを出力する
     *
     * 不正な操作が行われた時
     *
     * @param message ログに出力する内容
     */
    public static function setAlertLog($message = '')
    {
        self::setLog($message, self::LEVEL_ALERT);
    }

    /**
     * "Emergency" レベルのログを出力する
     *
     * 直ちに対応が必要となる場合
     *
     * @param message ログに出力する内容
     */
    public static function setEmergencyLog($message = '')
    {
        self::setLog($message, self::LEVEL_EMERGENCY);
    }

    /**
     * app/logs/log-$type-*.log ログファイル出力
     *
     * @param content: ログ内容（String or Array or Object）
     * @param fileName ファイル名（種別）
     * @param overwrite 上書きor日付で別ファイル保存
     * @param append 上書きして追記する
     */
    public static function writeLog($content, $fileName = 'all', $overwrite = false, $append = false)
    {
        global $kernel;

        $format = 'U.u';
        $date = \Datetime::createFromFormat($format, microtime(true));

        // 上書き
        if ($overwrite) {
            $fileName = 'log-' . $fileName . '.log';
        } else {
            $fileName = 'log-' . $fileName . '-' . $date->format('Y-m-d-H-i-s.u') . '.log';
        }

        // 追記
        if ($append) {
            $append = FILE_APPEND;
        }

        // ファイル保存先: app/logs/log-*.log
        $filePath = $kernel->getLogDir() . '/' . $fileName;

        if ($content) {
            if (is_array($content)) {
                file_put_contents($filePath, var_export($content, true), $append);
            } else if (is_object($content)) {
                file_put_contents($filePath, var_export($content, true), $append);
            } else {
                file_put_contents($filePath, $content, $append);
            }
        } else {
            file_put_contents($filePath, 'NULL', $append);
        }
    }

    /**
     * 配列の中にある空データを削除して配列を返す
     * 全角空白は半角空白に変換
     * 左右の半角空白は取り除く
     *
     * @return array
     */
    public static function mightRemoveEmptyInArray($array) {
        // 検索キーワード空チェック
        foreach ($array as $key => $value) {
            $array[$key] = trim(str_replace('　', ' ', $value));
            if ($array[$key] === '') {
                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * 文字コードを調べる
     */
    public static function getCharcode($str)
    {
        foreach(array('UTF-8','SJIS','EUC-JP','ASCII','JIS') as $charcode) {
            if(mb_convert_encoding($str, $charcode, $charcode) == $str) {
                return $charcode;
            }
        }

        return null;
    }

    /*
     * 入力されたものが日付かどうか判別する関数
     *
     */
    public static function isDate($date)
    {
        try {
            new \DateTime($date);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 設定ファイルのパラメータを取得
     */
    public static function getParameter($name)
    {
        global $kernel;
        return $kernel->getContainer()->getParameter($name);
    }

    /**
     * ログインユーザ情報を取得
     */
    public static function getLoginUser()
    {
        global $kernel;
        return $kernel->getContainer()->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * ログイン中のユーザー権限を判定
     *
     * @param  String $role 権限名
     * @return boolean
     */
    public static function isGranted($role)
    {
        global $kernel;
        return $kernel->getContainer()->get('security.authorization_checker')->isGranted($role);
    }

    /**
     * 環境判定
     *
     * @return boolean
     */
    public static function isEnv($env)
    {
        global $kernel;
        return $env === $kernel->getEnvironment();
    }

    /**
     * フォームの入力データを配列に変換
     *
     * @return Array
     */
    public static function toArrayByForm($form)
    {
        global $kernel;
        $request = $kernel->getContainer()->get('request');

        // 入力された検索条件取得
        $formDataArray = array();
        if ($request->getMethod() === 'POST') {
            $formDataArray = $request->request->get($form->getName());
            unset($formDataArray['_token']);
            unset($formDataArray['submit']);

            // 空要素チェック＆削除
            $formDataArray = self::mightRemoveEmptyInArray($formDataArray);

            $request->getSession()->set(Constants::SESSION_SAVE_KEYWORD, $formDataArray);
        } else {
            $formDataArray = $request->getSession()->get(Constants::SESSION_SAVE_KEYWORD);
        }

        return $formDataArray;
    }

    /**
     * 検索用 ページャーから並び順を取得
     *
     */
    public static function getOrderBySql($orderBy, $table = 't')
    {
        global $kernel;
        $request = $kernel->getContainer()->get('request');

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
     * スネークケースからパスカルケースへ変換
     * @return String
     */
    public static function toPascalcase($str)
    {
       return strtr(ucwords(strtr($str, ['_' => ' '])), [' ' => '']);
    }

    /**
     * スネークケースからキャメルケースへ変換
     * @return String
     */
    public static function toCamelcase($str)
    {
       return lcfirst(strtr(ucwords(strtr($str, ['_' => ' '])), [' ' => '']));
    }

    /**
     * キャメルケースからスネークケース（アンダースコア区切り）へ変換
     * @return String
     */
    public static function toSnakecase($str)
    {
       return ltrim(strtolower(preg_replace('/[A-Z]/', '_\0', $str)), '_');
    }

    /**
     * UNIX時間から日付文字列に変換
     * @param dateUnix = 1171502725
     * @return String
     */
    public static function toDateString($dateUnix, $format = 'Y-m-d H:i:s')
    {
        $dateStr = date($format, intval($dateUnix));
        return $dateStr;
    }

    /**
     * 日付文字列からUNIX時間に変換
     * @param dateStr = '2012/04/18 15:55:53'
     * @return Int
     */
    public static function toUnixTime($dateStr) {
        $dateUnix = strtotime($dateStr);
        return $dateUnix;
    }


    /*
     * ファイル名から拡張子を取得する関数
     *
     * http://takahashiyuya.hatenablog.com/entry/20110228/p1
     */
    public static function getExtension($filename, $lower = true)
    {
        if ($lower) {
            return strtolower(substr($filename, strrpos($filename, '.') + 1));
        } else {
            return strtoupper(substr($filename, strrpos($filename, '.') + 1));
        }
    }

    /**
     * 時刻を文字列に変換
     * @param $time
     */
    public static function timeToStr($time) {
        if (is_null($time)) {
            return null;
        }
        $retTimeStr = $time->format('Y-m-d H:i');
        return $retTimeStr;
    }

    /**
     * 対象のディレクトリの存在を確認し、なければディレクトリを作成する。
     * @return [type] [description]
     */
    public static function createDirectoryIfNeeded($dir)
    {
        if (!file_exists($dir)) {
            self::setDebugLog('createDirectoryIfNeeded: ' . $dir);
            if (mkdir($dir, 0777)) {
                chmod($dir, 0777);
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * アップロードディレクトリのパスを取得
     */
    public static function getUploadDir()
    {
        global $kernel;
        return $kernel->getContainer()->getParameter('upload_dir');
    }
}
