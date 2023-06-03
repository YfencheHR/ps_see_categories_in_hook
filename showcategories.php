<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class ShowCategories extends Module implements WidgetInterface
{
    public function __construct()
    {
        $this->name = 'showcategories';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Yfenche H.R.';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Mostrar Categorías en Inicio');
        $this->description = $this->l('Este módulo muestra una lista de categorías en la página de inicio.');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('displayHome')
            && $this->registerHook('displayHeader');
    }

    public function uninstall()
    {
        return parent::uninstall()
            && $this->unregisterHook('displayHome')
            && $this->unregisterHook('displayHeader');
    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name)) {
            $categories = Tools::getValue('showcategories_categories');
            Configuration::updateValue('SHOWCATEGORIES_CATEGORIES', implode(',', $categories));
            $output .= $this->displayConfirmation($this->l('Configuración guardada'));
        }

        $categories = Category::getCategories(Context::getContext()->language->id, true, false);
        $selectedCategories = explode(',', Configuration::get('SHOWCATEGORIES_CATEGORIES'));

        $options = array();
        foreach ($categories as $category) {
            $options[] = array(
                'id' => $category['id_category'],
                'name' => $category['name'],
                'selected' => in_array($category['id_category'], $selectedCategories)
            );
        }

        $module_link = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name
            . '&tab_module=' . $this->tab
            . '&module_name=' . $this->name
            . '&token=' . Tools::getAdminTokenLite('AdminModules');

        $this->context->smarty->assign(array(
            'module_dir' => $this->_path,
            'options' => $options,
            'selected_categories' => $selectedCategories,
            'module_link' => $module_link
        ));

        return $output.$this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $selectedCategories = explode(',', Configuration::get('SHOWCATEGORIES_CATEGORIES'));
        $categories = Category::getCategories(Context::getContext()->language->id,  true, false);
        $categories = array_filter($categories, function($category) use ($selectedCategories) {
            return in_array($category['id_category'], $selectedCategories);
        });

        foreach ($categories as &$category) {
            $category['image'] = $this->getCatImageLink(
                    $category['id_category'],
                    'medium_default',
                    $category['link_rewrite']
                );
        }

        return array(
            'categories' => $categories
        );
    }

    private function getCatImageLink($idCategory, $name , $link_rewrite)
    {
        $uriPath = __PS_BASE_URI__ . 'c/' . $idCategory  . '-' . $name . '/' . $link_rewrite . '.jpg';
        return  _PS_BASE_URL_SSL_ . $uriPath;
    }


    public function renderWidget($hookName = null, array $configuration = [])
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        return $this->fetch('module:showcategories/views/templates/hook/show_categories.tpl');
    }
    public function hookDisplayHeader()
    {
        $this->context->controller->registerStylesheet(
            'modules-showcategories', 'modules/' . $this->name . '/showcategories.css'
        );
    }
    public function hookDisplayHome($params)
    {
        return $this->renderWidget();
    }
}