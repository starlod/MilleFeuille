<?php

namespace AppBundle;

Class Util {
    /**
     * スネークケースからパスカルケースへ変換
     *
     * @param string $str
     * @return string
     */
    public static function toPascalcase($str)
    {
        return strtr(ucwords(strtr($str, ['_' => ' '])), [' ' => '']);
    }

    /**
     * スネークケースからキャメルケースへ変換
     *
     * @param string $str
     * @return string
     */
    public static function toCamelcase($str)
    {
        return lcfirst(strtr(ucwords(strtr($str, ['_' => ' '])), [' ' => '']));
    }

    /**
     * キャメルケースからスネークケース（アンダースコア区切り）へ変換
     *
     * @param string $str
     * @return string
     */
    public static function toSnakecase($str)
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_\0', $str)), '_');
    }

    /**
     * 対象のディレクトリの存在を確認し、なければディレクトリを作成する。
     *
     * @param string $dir
     * @return boolean
     */
    public static function mkdir($dir)
    {
        $fs = new Filesystem();
        try {
            $fs->mkdir($dir, 0777); // 既に存在しても例外は発生しない
        } catch (IOExceptionInterface $e) {
            echo $e->getMessage() . ':' . $e->getPath();
        }
        return true;
    }

    /**
     * 現在アクセスされているURLの絶対パスを返す
     *
     * @return string
     */
    public static function getCurrentUrl()
    {
        return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * 文字列検索::前方一致
     *
     * @param string $haystack 検索対象
     * @param string $needles  検索文字列
     * @param boolean $case    true: 大文字小文字を区別, false: 区別しない
     * @return boolean
     */
    public static function startsWith($haystack, $needles, $case = false)
    {
        $length = strlen($haystack);
        foreach ((array)$needles as $prefix) {
            $len = strlen($prefix);
            if (($len <= $length)
                && (substr_compare($haystack, $prefix, 0, $len, $case) === 0)
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * 文字列検索::後方一致
     *
     * @param string $haystack 検索対象
     * @param string $needles  検索文字列
     * @param boolean $case    true: 大文字小文字を区別, false: 区別しない
     * @return boolean
     */
    public static function endsWith($haystack, $needles, $case = false)
    {
        $length = strlen($haystack);
        foreach ((array)$needles as $suffix) {
            $len = strlen($suffix);
            if (($len <= $length)
                && (substr_compare($haystack, $suffix, -$len, $len, $case) === 0)
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * 文字列検索::部分一致
     *
     * @param string $haystack 検索対象
     * @param string $needles  検索文字列
     * @param boolean $case    true: 大文字小文字を区別, false: 区別しない
     * @return boolean
     */
    public static function contains($haystack, $needles, $case = false)
    {
        $func = ($case ? 'stripos' : 'strpos');
        foreach ((array)$needles as $needle) {
            if ($func($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * 日付文字列からUNIX時間に変換
     *
     * @param string $str '2012/04/18 15:55:53'
     * @return int
     */
    public static function toUnixTime($dateStr)
    {
        return strtotime($dateStr);
    }

    /**
     * 配列の最初のkey値を取得する（連想配列にも対応）
     *
     * @param array $array
     * @return int|string
     */
    public static function getFirstKey($array)
    {
        return key(array_slice($array, 0, 1, true));
    }

    /**
     * 配列の最後のkey値を取得する（連想配列にも対応）
     *
     * @param array $array
     * @return int|string
     */
    public static function getLastKey($array)
    {
        return key(array_slice($array, -1, 1, true));
    }

    /**
     * 指定ディレクトリ内のファイルのファイルパス配列を返す
     *
     * @param string $dir
     * @return array
     */
    public static function getFileList($dir)
    {
        $finder = new Finder();
        $iterator = $finder
            ->in($dir)
            ->name('*.*')
            ->files();
        $list = [];
        foreach ($iterator as $fileinfo) {
            $list[] = $fileinfo->getPathname();
        }
        return $list;
    }

    /**
     * 指定したショートカットリンク、ファイル、ディレクトリの削除（ディレクトリ内も再帰的に削除する）
     *
     * @param string|array $items ファイルパス、文字列の配列
     * @return boolean
     */
    public static function removeItems($items)
    {
        $fs = new Filesystem();
        try {
            $fs->remove($items);
        } catch (IOExceptionInterface $e) {
            echo $e->getMessage() . ':' . $e->getPath();
        }
    }
}