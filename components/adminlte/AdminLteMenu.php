<?php

namespace app\components\adminlte;

use app\components\helpers\PermissionHelper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * Class Menu
 * Theme menu widget.
 */
class AdminLteMenu extends \yii\widgets\Menu
{
    /**
     * @inheritdoc
     */
    public $linkTemplate = '<a class="nav-link {active}" href="{url}">{icon} {label}</a>';
    public $labelTemplate = '<p>{label}</p>';
    /**
     * @inheritdoc
     * Styles all labels of items on sidebar by AdminLTE
     */
    public $submenuTemplate = "\n<ul class='nav nav-treeview' {show}>\n{items}\n</ul>\n";
    public $activateParents = true;
    public $defaultIconHtml = '<i class="fa fa-circle-o"></i> ';
    public $options = [
        'class' => 'sidebar-menu',
        'data-widget' => 'treeview',
        'role' => 'menu',
        'data-accordion' => 'false',
    ];
    /**
     * @var string is prefix that will be added to $item['icon'] if it exist.
     * By default uses for Font Awesome (http://fontawesome.io/)
     */
    public static $iconClassPrefix = 'nav-icon fas fa-';
    private $noDefaultAction;
    private $noDefaultRoute;

    /**
     * Renders the menu.
     */
    public function run()
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = $this->normalizeRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
        $posDefaultAction = strpos($this->route, Yii::$app->controller->defaultAction);
        if ($posDefaultAction) {
            $this->noDefaultAction = rtrim(substr($this->route, 0, $posDefaultAction), '/');
        } else {
            $this->noDefaultAction = false;
        }
        $posDefaultRoute = strpos($this->route, Yii::$app->controller->module->defaultRoute);
        if ($posDefaultRoute) {
            $this->noDefaultRoute = rtrim(substr($this->route, 0, $posDefaultRoute), '/');
        } else {
            $this->noDefaultRoute = false;
        }
        $items = $this->normalizeItems($this->items, $hasActiveChild);
        if (!empty($items)) {
            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'ul');
            echo Html::tag($tag, $this->renderItems($items), $options);
        }
    }

    protected function normalizeRoute()
    {
        $route = Yii::$app->controller->getRoute();
        $routePart = explode('/', $route);
        if (count($routePart) === 3) {
            if (in_array($routePart[2], ['create', 'update', 'view'])) {
                $routePart[2] = 'index';
            }
            return implode('/', $routePart);
        }
        return $route;
    }

    /**
     * @inheritdoc
     */
    protected function normalizeItems($items, &$active)
    {
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            } elseif (!isset($item['visible']) && !empty($item['url'])) {
                $url = is_array($item['url']) ? $item['url'][0] : $item['url'];
                if (!PermissionHelper::checkPermission($url, true)) {
                    unset($items[$i]);
                    continue;
                }
            }

            $label = $item['label'] ?? '';
            $items[$i]['label'] = ($item['encode'] ?? $this->encodeLabels)
                ? Html::encode($label)
                : $label;
            $items[$i]['icon'] = $item['icon'] ?? '';

            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                if (empty($items[$i]['items']) && $this->hideEmptyItems) {
                    unset($items[$i]['items']);
                    if (!isset($item['url'])) {
                        unset($items[$i]);
                        continue;
                    }
                }
            }

            if (!isset($item['active'])) {
                if ($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item)) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active']) {
                $active = true;
            }
        }
        return array_values($items);
    }

    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];
            if (isset($route[0]) && $route[0] !== '/' && Yii::$app->controller) {
                $route = ltrim(Yii::$app->controller->module->getUniqueId() . '/' . $route, '/');
            }
            $route = ltrim($route, '/');
            if ($route != $this->route && $route !== $this->noDefaultRoute && $route !== $this->noDefaultAction) {
                return false;
            }
            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                foreach (array_splice($item['url'], 1) as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge(
                ['class' => 'nav-item'],
                $this->itemOptions,
                ArrayHelper::getValue($item, 'options', [])
            );
            $tag = ArrayHelper::remove($options, 'tag', 'li');

            $class = [];
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }

            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }

            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                $menu .= strtr($this->submenuTemplate, [
                    '{show}' => $item['active'] ? "style='display: block'" : '',
                    '{items}' => $this->renderItems($item['items']),
                ]);
                if (isset($options['class'])) {
                    $options['class'] .= ' has-treeview';
                } else {
                    $options['class'] = 'has-treeview';
                }
                if ($item['active']) {
                    $options['class'] .= ' menu-open';
                }
            }
            $lines[] = Html::tag($tag, $menu, $options);
        }
        return implode("\n", $lines);
    }

    /**
     * @inheritdoc
     */
    protected function renderItem($item)
    {
        if (isset($item['items'])) {
            $labelTemplate = '<a href="{url}" class="nav-link {active}">{icon} {label-fa} </a>';
            $linkTemplate = '<a href="{url}" class="nav-link {active}">{icon} {label-fa} </a>';
        } else {
            $labelTemplate = $this->labelTemplate;
            $linkTemplate = $this->linkTemplate;
        }
        $replacements = [
            '{label}' => "<p>{$item['label']}</p>",
            '{label-fa}' => "<p>{$item['label']}<i class=\"right fas fa-angle-left\"></i></p>",
            '{icon}' => empty($item['icon'])
                ? $this->defaultIconHtml
                : '<i class="' . static::$iconClassPrefix . $item['icon'] . '"></i> ',
            '{url}' => isset($item['url']) ? Url::to($item['url']) : '#',
            '{active}' => !empty($item['active']) ? $this->activeCssClass : '',
        ];
        $template = ArrayHelper::getValue($item, 'template', isset($item['url'])
            ? $linkTemplate
            : $labelTemplate
        );
        return strtr($template, $replacements);
    }

}