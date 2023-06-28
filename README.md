## Drupal Python
This module provides a simple service which wraps around a shell exec command
to run Python scripts within Drupal development workflows.

### Installation
Add this to your project `composer.json` file in the `repositories` section
```
"drupal/drupal_python": {
    "type": "vcs",
    "url": "https://github.com/dan612/drupal_python.git"
}
```
Then run:
```
composer require drupal/drupal_python
```

### How to set up
After enabling the module, go to `/admin/config/drupal_python` and set the path to where your
python scripts are stored. Once you add scripts there they will
be autoloaded to the service and ready for usage. The script name will
be stored as the filename without the `.py` extension, i.e. `getTime.py`
would be `getTime`.

### Usage
Say you want to change the page title via Python, you could do:

```
/**
 * Implements hook_preprocess_HOOK() for page_title template.
 */
function drupal_python_preprocess_page_title(&$vars) {
  $node = \Drupal::routeMatch()->getParameter('node');
  $python = \Drupal::service('drupal_python.conduit');
  $time = $python->runScript('getTime', 3);
  $vars['title'] = $node->getTitle() . " -- " . $time;
}
```
With a python script such as:
```
from datetime import datetime

now = datetime.now()
current_time = now.strftime("%H:%M:%S")

print("Current Time:", current_time)

```
