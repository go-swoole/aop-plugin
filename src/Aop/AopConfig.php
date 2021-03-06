<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/23
 * Time: 18:27
 */

namespace ESD\Plugins\Aop;


use ESD\Core\Plugins\Config\BaseConfig;
use ESD\Core\Plugins\Config\ConfigException;

class AopConfig extends BaseConfig
{
    const key = "aop";
    /**
     * Cache directory
     * @var string
     */
    protected $cacheDir;
    /**
     * Include paths restricts the directories where aspects should be applied
     * @var string[]
     */
    protected $includePaths = [];
    /**
     * Exclude paths restricts the directories where aspects should be applied
     * @var string[]
     */
    protected $excludePaths = [];
    /**
     * 是否文件缓存，默认内存缓存
     * @var bool
     */
    protected $fileCache = false;
    /**
     * @var OrderAspect[]
     */
    private $aspects = [];

    public function __construct(...$includePaths)
    {
        parent::__construct(self::key);
        foreach ($includePaths as $includePath) {
            $this->addIncludePath($includePath);
        }
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * @param string $cacheDir
     */
    public function setCacheDir(string $cacheDir): void
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * @return string[]
     */
    public function getIncludePaths()
    {
        return $this->includePaths;
    }

    /**
     * @param string[] $includePaths
     */
    public function setIncludePaths(array $includePaths): void
    {
        $this->includePaths = $includePaths;
    }

    /**
     * @param string $includePath
     */
    public function addIncludePath(string $includePath)
    {
        $includePath = realpath($includePath);
        if ($includePath === false) return;
        $key = str_replace(realpath(ROOT_DIR), "", $includePath);
        $key = str_replace("/", ".", $key);
        $this->includePaths[$key] = $includePath;
    }

    public function addAspect(OrderAspect $param)
    {
        $this->aspects[] = $param;
    }

    /**
     * @return OrderAspect[]
     */
    public function getAspects(): array
    {
        return $this->aspects;
    }

    /**
     * 构建config
     * @throws ConfigException
     */
    public function buildConfig()
    {
        if (empty($this->includePaths)) {
            throw new ConfigException("includePaths不能为空");
        }
    }

    /**
     * @return bool
     */
    public function isFileCache(): bool
    {
        return $this->fileCache;
    }

    /**
     * @param bool $fileCache
     */
    public function setFileCache(bool $fileCache): void
    {
        $this->fileCache = $fileCache;
    }

    /**
     * @return string[]
     */
    public function getExcludePaths(): array
    {
        return $this->excludePaths;
    }

    /**
     * @param string[] $excludePaths
     */
    public function setExcludePaths(array $excludePaths): void
    {
        $this->excludePaths = $excludePaths;
    }

    /**
     * @param string $excludePath
     */
    public function addExcludePath(string $excludePath)
    {
        $excludePath = realpath($excludePath);
        if ($excludePath === false) return;
        $key = str_replace(realpath(ROOT_DIR), "", $excludePath);
        $key = str_replace("/", ".", $key);
        $this->excludePaths[$key] = $excludePath;
    }
}