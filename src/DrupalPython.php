<?php

namespace Drupal\drupal_python;

use Drupal\Core\Config\ConfigFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DrupalPython {

  /**
   * Array of python scripts in stored folder.
   *
   * @var array
   */
  private $scripts = [];

  /**
   * The current environment version of Python.
   *
   * @var string
   */
  private $pythonVersion;

  /**
   * Path to Python scripts.
   */
  public $pythonScriptsPath;

  /**
   * Public constructor.
   */
  public function __construct(ConfigFactory $config) {
    // Set the Python version from the env.
    $this->setPythonVersion();
    // Load all scripts from defined dir.
    $this->pythonScriptsPath = $config->get('drupal_python.settings')->get('scripts_path');
    // Make sure .py extension on script.
    $scripts = glob($this->pythonScriptsPath . '/*.py');
    foreach ($scripts as $script_path) {
      // Ensure not a blank file, somehow.
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
   *
   * @return false|string|void|null
   */
  public function runScript($script_name, $args = []) {
    if (!isset($this->scripts[$script_name])) {
      return FALSE;
    }
    $script_path = $this->scripts[$script_name];
    $cmd = [
      $this->pythonVersion,
      $script_path
    ];
    if (!empty($args)) {
      foreach ($args as $arg) {
        $cmd[] = $arg;
      }
    }
    $process = new Process($cmd);
    $process->run();
    if (!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
    }

    return $process->getOutput();
  }

  /**
   * Detect the installed Python version.
   *
   * @return void
   */
  public function setPythonVersion() {
    $python_versions = [
      'python',
      'python2',
      'python3',
    ];
    foreach ($python_versions as $python_version) {
      $cmd = [
        $python_version,
        '--version',
      ];
      $process = new Process($cmd);
      $process->run();
      if ($process->isSuccessful()) {
        $this->pythonVersion = $python_version;
      }
    }
  }

}
