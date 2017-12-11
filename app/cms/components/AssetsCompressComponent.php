<?php
namespace module\cms\components;

use Yii;
use yii\helpers\FileHelper;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Application;
use yii\web\Response;
use yii\web\View;

/**
 * Class AssetsCompressComponent
 */
class AssetsCompressComponent extends \iisns\assets\AssetsCompressComponent
{
    /**
     * @param array $files
     * @return array
     */
    protected function _processingJsFiles($files = [])
    {
        //$fileName   =  md5( implode(array_keys($files)) . $this->getSettingsHash()) . '.js';
        $fileName   = $this->buildFileName($files, '.js'); 
        $publicUrl  = Yii::getAlias('@web/assets/js-compress/' . $fileName);
        $rootDir    = Yii::getAlias('@webroot/assets/js-compress');
        $rootUrl    = $rootDir . '/' . $fileName;

        if (file_exists($rootUrl)) {
            $resultFiles        = [];
            foreach ($files as $fileCode => $fileTag) {
                if (!Url::isRelative($fileCode)) {
                    $resultFiles[$fileCode] = $fileTag;
                } else {
                    if ($this->jsFileRemouteCompile) {
                        $resultFiles[$fileCode] = $fileTag;
                    }
                }
            }
            $publicUrl                  = $publicUrl . "?v=" . filemtime($rootUrl);
            $resultFiles[$publicUrl]    = Html::jsFile($publicUrl);
            return $resultFiles;
        }

        $resultContent  = [];
        $resultFiles    = [];
        foreach ($files as $fileCode => $fileTag) {
            if (Url::isRelative($fileCode)) {
                $resultContent[] = trim($this->fileGetContents($fileCode)) . "\n;";
            } else {
                $resultFiles[$fileCode] = $fileTag;
            }

        }
        if ($resultContent) {
            $content = implode($resultContent, ";\n");
            if (!is_dir($rootDir)) {
                if (!FileHelper::createDirectory($rootDir, 0777)) {
                    return $files;
                }
            }
            if ($this->jsFileCompress) {
                $content = \JShrink\Minifier::minify($content, ['flaggedComments' => $this->jsFileCompressFlaggedComments]);
            }
            $file = fopen($rootUrl, "w");
            fwrite($file, $content);
            fclose($file);
        }
        if (file_exists($rootUrl)) {
            $publicUrl                  = $publicUrl . "?v=" . filemtime($rootUrl);
            $resultFiles[$publicUrl]    = Html::jsFile($publicUrl);
            return $resultFiles;
        } else {
            return $files;
        }
    }

    /**
     * @param array $files
     * @return array
     */
    protected function _processingCssFiles($files = [])
    {
        //$fileName   =  md5( implode(array_keys($files)) . $this->getSettingsHash() ) . '.css';
        $fileName   = $this->buildFileName($files, '.css'); 
        $publicUrl  = Yii::getAlias('@web/assets/css-compress/' . $fileName);
        $rootDir    = Yii::getAlias('@webroot/assets/css-compress');
        $rootUrl    = $rootDir . '/' . $fileName;

        if (file_exists($rootUrl)) {
            $resultFiles = [];
            foreach ($files as $fileCode => $fileTag) {
                if (strpos($fileTag, 'no-merge') !== false) {
                    $resultFiles[$fileCode] = $fileTag;
                    continue;
                }

                if (!Url::isRelative($fileCode) && !$this->cssFileRemouteCompile) {
                    $resultFiles[$fileCode] = $fileTag;
                }
            }
            $publicUrl                  = $publicUrl . "?v=" . filemtime($rootUrl);
            $resultFiles[$publicUrl]    = Html::cssFile($publicUrl);
            return $resultFiles;
        }

        $resultContent  = [];
        $resultFiles    = [];
        foreach ($files as $fileCode => $fileTag) {
            if (strpos($fileTag, 'no-merge') !== false) {
                $resultFiles[$fileCode] = $fileTag;
                continue;
            }

            if (Url::isRelative($fileCode)) {
                $contentTmp  = trim($this->fileGetContents( $fileCode ));
                $fileCodeTmp = explode("/", $fileCode);
                unset($fileCodeTmp[count($fileCodeTmp) - 1]);
                $prependRelativePath = implode("/", $fileCodeTmp) . "/";
                $contentTmp = \Minify_CSS::minify($contentTmp, [
                    "prependRelativePath" => $prependRelativePath,
                    'compress'          => true,
                    'removeCharsets'    => true,
                    'preserveComments'  => true,
                ]);
                //$contentTmp = \CssMin::minify($contentTmp);
                $resultContent[] = $contentTmp;
            } else {
                if ($this->cssFileRemouteCompile) {
                    //Trying to download a remote file
                    $resultContent[] = trim($this->fileGetContents( $fileCode ));
                } else {
                    $resultFiles[$fileCode] = $fileTag;
                }
            }
        }
        if ($resultContent) {
            $content = implode($resultContent, "\n");
            if (!is_dir($rootDir)) {
                if (!FileHelper::createDirectory($rootDir, 0777)) {
                    return $files;
                }
            }
            if ($this->cssFileCompress) {
                $content = \CssMin::minify($content);
            }
            $file = fopen($rootUrl, "w");
            fwrite($file, $content);
            fclose($file);
        }
        if (file_exists($rootUrl)) {
            $publicUrl                  = $publicUrl . "?v=" . filemtime($rootUrl);
            $resultFiles[$publicUrl]    = Html::cssFile($publicUrl);
            return $resultFiles;
        } else {
            return $files;
        }
    }

    public function buildFileName($files, $ext = '.css')
    {
        $webroot = Yii::getAlias('@webroot');

        $filetimes = [];
        foreach ($files as $file => $fileTag) {
            if (! Url::isRelative($file)) {
                continue;
            }
            if (strpos($fileTag, 'no-merge') !== false) {
                continue;
            }

            if (substr($file, 0, 1) !== '/') {
                $file .= '/';
            }

            if (file_exists($webroot.$file)) {
                $filetimes[] = filemtime($webroot.$file);
            }
        }

        return md5( implode($filetimes) . $this->getSettingsHash() ) . $ext;
    }

    /**
     * Read file contents
     *
     * @param $file
     * @return string
     */
    public function fileGetContents($file)
    {
        if (substr($file, 0, 1) !== '/') {
            $file .= '/';
        }
        if ($qPos = strpos($file, '?')) {
            $file = substr($file, 0, $qPos);
        }
        $file = Yii::getAlias('@webroot').$file;
        if (file_exists($file)) {
            return file_get_contents($file);
        }
        return '';
    }
}
