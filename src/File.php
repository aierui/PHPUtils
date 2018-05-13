<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/3/29
 * Time: 下午11:56
 */

namespace PHPUtils;


class File
{

    public static function tryLock($filePath)
    {
        try {
            if (!file_exists($filePath)) {
                return false;
            }
            $fp = fopen($filePath, 'rb');
            if (flock($fp, LOCK_EX | LOCK_NB)) {
                return $fp;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return false;
        }
    }


    public static function tryUnlock($filePath): bool
    {
        try {
            flock($filePath, LOCK_UN);
            fclose($filePath);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function createFile($filePath, $overwrite = true): bool
    {
        if (file_exists($filePath) && $overwrite == false) {
            return false;
        } elseif (file_exists($filePath) && $overwrite == true) {
            if (!self::deleteFile($filePath)) {
                return false;
            }
        }
        $aimDir = dirname($filePath);
        if (self::createDir($aimDir)) {
            try {
                return touch($filePath);
            } catch (\Throwable $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function saveFile($filePath, $content, $overwrite = true): bool
    {
        if (self::createFile($filePath, $overwrite)) {
            return (bool)file_put_contents($filePath, $content);
        } else {
            return false;
        }
    }

    public static function copyFile($filePath, $targetFilePath, $overwrite = true): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }
        if (file_exists($targetFilePath) && $overwrite == false) {
            return false;
        } elseif (file_exists($targetFilePath) && $overwrite == true) {
            if (!self::deleteFile($targetFilePath)) {
                return false;
            }
        }
        $aimDir = dirname($filePath);
        if (!self::createDir($aimDir)) {
            return false;
        };
        return copy($filePath, $targetFilePath);
    }

    public static function createDir($dirPath): bool
    {
        if (!is_dir($dirPath)) {
            try {
                return mkdir($dirPath, 0755, true);
            } catch (\Throwable $e) {
                return false;
            }
        } else {
            return true;
        }
    }

    public static function deleteDir($dirPath): bool
    {
        if (self::clearDir($dirPath)) {
            try {
                return rmdir($dirPath);
            } catch (\Throwable $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function clearDir($dirPath): bool
    {
        if (!is_dir($dirPath)) {
            return false;
        }
        try {
            $dirHandle = opendir($dirPath);
            if (!$dirHandle) {
                return false;
            }
            while (false !== ($file = readdir($dirHandle))) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (!is_dir($dirPath . "/" . $file)) {
                    if (!self::deleteFile($dirPath . "/" . $file)) {
                        closedir($dirHandle);
                        return false;
                    }
                } else {
                    if (!self::deleteDir($dirPath . "/" . $file)) {
                        closedir($dirHandle);
                        return false;
                    }
                }
            }
            closedir($dirHandle);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function copyDir($dirPath, $targetPath, $overwrite = true): bool
    {
        if (!is_dir($dirPath)) {
            return false;
        }
        if (!file_exists($targetPath)) {
            if (!self:: createDir($targetPath)) {
                return false;
            }
        }
        try {
            $dirHandle = opendir($dirPath);
            if (!$dirHandle) {
                return false;
            }
            while (false !== ($file = readdir($dirHandle))) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (!is_dir($dirPath . "/" . $file)) {
                    if (!self::copyFile($dirPath . "/" . $file, $targetPath . "/" . $file, $overwrite)) {
                        closedir($dirHandle);
                        return false;
                    }
                } else {
                    if (!self::copyDir($dirPath . "/" . $file, $targetPath . "/" . $file, $overwrite)) {
                        closedir($dirHandle);
                        return false;
                    };
                }
            }
            closedir($dirHandle);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function moveDir($dirPath, $targetPath, $overwrite = true): bool
    {
        try {
            if (self::copyDir($dirPath, $targetPath, $overwrite)) {
                return self::deleteDir($dirPath);
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return false;
        }
    }


    public static function moveFile($filePath, $targetFilePath, $overwrite = true): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }
        if (file_exists($targetFilePath) && $overwrite == false) {
            return false;
        } elseif (file_exists($targetFilePath) && $overwrite == true) {
            if (!self::deleteFile($targetFilePath)) {
                return false;
            }
        }
        $targetDir = dirname($targetFilePath);
        if (!self:: createDir($targetDir)) {
            return false;
        }
        return rename($filePath, $targetFilePath);
    }

    public static function deleteFile($filePath): bool
    {
        try {
            unlink($filePath);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function listDir($dir, $suffix = ''): array
    {
        $files = [];
        if (!is_dir($dir)) return $files;
        $handle = @opendir($dir);
        if (!$handle) return $files;
        while (($file = readdir($handle)) !== false) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $filepath = $dir == '.' ? $file : $dir . '/' . $file;

            if (is_link($filepath)) {
                continue;
            }

            if (is_file($filepath)) {
                if ($suffix == substr($filepath, -strlen($suffix), strlen($suffix))) {
                    $files[] = $filepath;
                }
            } else if (is_dir($filepath)) {
                $files = array_merge($files, self::listDir($filepath, $suffix));
            }
        }
        closedir($handle);
        return $files;
    }

    public static function getDirFile($path, $fileregular = '')
    {
        $path = rtrim($path, "/");
        if (!is_dir($path)) return array();
        $list = @scandir($path);
        if ($list === false) return array();
        $files = array();
        foreach ($list as $filename) {
            $file = $path . '/' . $filename;
            if (!is_file($file)) continue;
            if (!empty($fileregular)) {
                if (!preg_match($fileregular, $filename)) continue;
            }
            $files[] = $file;
        }
        return $files;
    }
}