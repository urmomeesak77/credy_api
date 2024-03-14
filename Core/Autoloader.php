<?php

  namespace Core;


  class Autoloader  {
    /**
   * @var $loaders Autoloader[]
   */
    protected static $loaders;

    protected static $loaderInitialized = false;


    public function register() {
      self::registerLoader($this);
    }

    /**
     * @param Autoloader $Loader
     */
    public static function registerLoader(Autoloader $Loader) {
      self::$loaders[] = $Loader;
      if (!self::$loaderInitialized) {
        self::initAutoLoader();
      }
    }


    public static function initAutoLoader() {
      spl_autoload_register(array('\Core\Autoloader', 'loadClass'));
      self::$loaderInitialized = true;
    }


    /**
     * @param string $className
     */
    public final static function loadClass(string $className) {
      foreach (self::$loaders as $Loader) {
        if ($file = $Loader->findFileForClass($className)) {
          include_once $file;
          return;
        }
      }
      throw new Autoloader\Exception('Could not load class ' . $className);
    }


    /**
     * @param string $className
     *
     * @return null|string
     */
    public function findFileForClass(string $className) {
      $className = str_replace('_', '\\', $className);
      if (stripos($className, __NAMESPACE__ . '\\') === false) {
        return null;
      }

      $className = explode('\\', $className);
      array_shift($className);
      $className = implode(DIRECTORY_SEPARATOR, $className);

      $file = realpath(__DIR__ ) . DIRECTORY_SEPARATOR . $className . '.php';

      if (file_exists($file)) {
        return $file;
      }
      return null;
    }
  }

