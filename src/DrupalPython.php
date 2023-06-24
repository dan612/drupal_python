<?php

namespace Drupal\drupal_python;

use Drupal\Core\Config\ConfigFactory;

class DrupalPython {

  /**
   * Array of python scripts in stored folder.
   *
   * @var array
   */
  private $scripts = [];

  /**
   * Path to Python scripts.
   */
  public $pythonScriptsPath;

  /**
   * Public constructor.
   */
  public function __construct(ConfigFactory $config) {
    $this->pythonScriptsPath = $config->get('drupal_python.settings')->get('scripts_path');
    $scripts = glob($this->pythonScriptsPath . '/*.py');
    foreach ($scripts as $script_path) {
      if (strlen($script_path) > 0) {
        $script_name = str_replace('.py', '', basename($script_path));
        $this->scripts[$script_name] = $script_path;
      }
    }
  }

  /**
   * Run a script in Python.
   *
   * @param $script_name
   *   The name of the script, without ".py" extension.
   * @param $python_ver
   *   Version of python to run command in.
   * @return false|string|void|null
   */
  public function runScript($script_name, $python_ver = 3) {
    if (isset($this->scripts[$script_name])) {
      $script_path = $this->scripts[$script_name];
      return shell_exec('python' . $python_ver .  " $script_path");
    }
  }

}
