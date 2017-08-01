<?php

class blog extends Module
{
    public function __construct()
    {
        $this->name = 'blog';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Romain';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Blog Module');
        $this->description = $this->l('Awesome Module for Blog stuffs.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('BLOG_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }
    public function install()
    {

        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install()
            || !$this->registerHook('leftColumn')
            || !$this->registerHook('header')
            || !Configuration::updateValue('BLOG_NAME', 'hello world')
            || !$this->installDb()
            || !$this->addAdminTab()
        ) {
            return false;
        }

        return true;
    }

    public  function installDb()

    {

        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'blog` (

            `id_blog` int(11) NOT NULL AUTO_INCREMENT,

            `blog_name` varchar(50) NOT NULL,

            `blog_description` varchar(200) NOT NULL,

            `date_blog` datetime NOT NULL,

            PRIMARY KEY (`id_blog`))';

        return Db::getInstance()->execute($sql);

    }

    public function uninstallDb()

    {

             $sql = 'DROP TABLE '._DB_PREFIX_.'blog';

             Db::getInstance()->execute($sql);

             return true;

    }

    public function uninstall()

    {

        if (!parent::uninstall()

            || !Configuration::deleteByName('BLOG_NAME')
            || !$this->uninstallDb()
            || !$this->removeAdminTab()

        ) {

            return false;

        }

        return true;

    }

    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name)) {
            $blog_name = strval(Tools::getValue('BLOG_NAME'));
            if (!$blog_name
          || empty($blog_name)
          || !Validate::isGenericName($blog_name)) {
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            } else {
                Configuration::updateValue('BLOG_NAME', $blog_name);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        return $output.$this->displayForm();
    }
    public function displayForm()
    {
        // Get default language
    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

    // Init Fields form array
    $fields_form[0]['form'] = array(
        'legend' => array(
            'title' => $this->l('Settings'),
        ),
        'input' => array(
            array(
                'type' => 'text',
                'label' => $this->l('Configuration value'),
                'name' => 'BLOG_NAME',
                'size' => 20,
                'required' => true
            )
        ),
        'submit' => array(
            'title' => $this->l('Save'),
            'class' => 'btn btn-default pull-right'
        )
    );

        $helper = new HelperForm();

    // Module, token and currentIndex
    $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

    // Language
    $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

    // Title and toolbar
    $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
    $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
        'save' =>
        array(
            'desc' => $this->l('Save'),
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
            '&token='.Tools::getAdminTokenLite('AdminModules'),
        ),
        'back' => array(
            'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Back to list')
        )
    );

    // Load current value
    $helper->fields_value['BLOG_NAME'] = Configuration::get('BLOG_NAME');

        return $helper->generateForm($fields_form);
        $helper = new HelperForm();

    // Module, Token and currentIndex
    $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

    // Language
    $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

    // title and Toolbar
    $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
    $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
        'save' =>
        array(
            'desc' => $this->l('Save'),
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
            '&token='.Tools::getAdminTokenLite('AdminModules'),
        ),
        'back' => array(
            'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Back to list')
       )
    );

    // Load current value
    $helper->fields_value['BLOG_NAME'] = Configuration::get('BLOG_NAME');

        return $helper->generateForm($fields_form);
    }
    public function hookDisplayLeftColumn($params)
    {
        $productObj = new Product();
        $products = $productObj->getProducts(Context::getContext()->language->id, 0, 0, 'id_product', 'DESC', false, true);
        $this->context->smarty->assign(
      array(
          'blog_name' => Configuration::get('BLOG_NAME'),
          'blog_link' => $this->context->link->getModuleLink('blog', 'display'),
          'blog_count' => count($products),
          'blog_last_product' => $products[0][name]
      )
  );
        return $this->display(_PS_MODULE_DIR_.$this->name, 'blog.tpl');
    }

    public function hookDisplayRightColumn($params)
    {
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'modules/mymodule/css/mymodule.css', 'all');
    }

    public function addAdminTab()

    {

        // création de l'onglet

        $tab = new Tab();

        foreach(Language::getLanguages(false) as $lang)

            $tab->name[(int) $lang['id_lang']] = 'Blog';

        // Nom du controller sans le mot clé "Controller"

        $tab->class_name = 'AdminBlog';

        $tab->module = $this->name;

        $tab->id_parent = 0;

        if (!$tab->save())

            return false;

        return true;

    }

    // Suppression d'onglets

    public function removeAdminTab()

    {

        $classNames = array('admin_blog' => 'AdminBlog');

        $return = true;

        foreach ($classNames as $key => $className) {

            $tab = new Tab(Tab::getIdFromClassName($className));

            $return &= $tab->delete();

        }

        return $return;

    }
}
