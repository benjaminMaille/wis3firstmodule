<?php
/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once 'classes/ProductWIS3.php';

class Wis3FirstModule extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'wis3firstmodule';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'moi';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('wis3 first module');
        $this->description = $this->l('it\'s my first module !');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        include dirname(__FILE__) . '/sql/install.php';

        Configuration::updateValue('WIS3FIRSTMODULE_DISPLAY_PRODUCT_PLUS', false);
        Configuration::updateValue('WIS3FIRSTMODULE_DISPLAY_PRICE_WT', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayRightColumnProduct') &&
            $this->registerHook('displayFooterProduct') &&
            $this->registerHook('displayProductButtons') &&
            $this->registerHook('displayAdminProductsExtra') &&
            $this->registerHook('actionProductUpdate');            
    }

    public function uninstall()
    {
        Configuration::deleteByName('WIS3FIRSTMODULE_DISPLAY_PRODUCT_PLUS');
        Configuration::deleteByName('WIS3FIRSTMODULE_DISPLAY_PRICE_WT');        

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitWis3FirstModuleModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitWis3FirstModuleModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Further informations on product page'),
                        'name' => 'WIS3FIRSTMODULE_DISPLAY_PRODUCT_PLUS',
                        'is_bool' => true,
                        'desc' => $this->l('Display further informations on product page'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display price without tax'),
                        'name' => 'WIS3FIRSTMODULE_DISPLAY_PRICE_WT',
                        'is_bool' => true,
                        'desc' => $this->l('Display price without tax on product page (and maybe somewhere else)'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'WIS3FIRSTMODULE_DISPLAY_PRODUCT_PLUS' => Configuration::get('WIS3FIRSTMODULE_DISPLAY_PRODUCT_PLUS'),
            'WIS3FIRSTMODULE_DISPLAY_PRICE_WT' => Configuration::get('WIS3FIRSTMODULE_DISPLAY_PRICE_WT'),            
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

	public function hookDisplayRightColumnProduct($params)
	{
		//return $this->hookDisplayProductFurtherInformations();
		return $this->displayAllProduct();
	}



    public function hookDisplayFooterProduct($params)
	{
		//return $this->hookDisplayProductFurtherInformations();
	}

    public function hookDisplayProductButtons($params)
	{
		//return $this->hookDisplayProductFurtherInformations();
	}

    public function hookDisplayProductFurtherInformations()
    {
        if (Configuration::get('WIS3FIRSTMODULE_DISPLAY_PRODUCT_PLUS')) {
            if (isset($this->context->controller) && method_exists($this->context->controller, 'getProduct')) {
                $product = $this->context->controller->getProduct();
                if (isset($product) && Validate::isLoadedObject($product)) {
                    $product_wis3 = ProductWIS3::getProductWIS3($product->id);
                    $this->context->smarty->assign(array(
                        'product_further_information' => isset($product_wis3) ? $product_wis3->comment : '',
                    ));
                    return $this->display(__FILE__, 'productfurtherinformation.tpl');
                }
            }
        }
        return false;
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        if ($product_wis3 = ProductWIS3::getProductWIS3((int)Tools::getValue('id_product'))) {
            $this->context->smarty->assign(array(
                'product_wis3' => $product_wis3,
            ));
            return $this->display(__FILE__, 'product_wis3.tpl');
        }
        return false;
    }

    public function hookActionProductUpdate($params)
    {
        if ($product_wis3 = ProductWIS3::getProductWIS3((int)Tools::getValue('id_product'))) {
            $product_wis3->comment = Tools::getValue('comment');
            $product_wis3->save();
        }
    }

	public function displayAllProduct ()
	{
		$product = $this->context->controller->getProduct();
        
        $category = new Category ($product->id_category_default);

        $same_products = $category->getProducts(
            
            $this->context->language->id,
            1,
            100
        );

        var_dump($same_products);

		$this->context->smarty->assign(array("same_products" => $same_products));

		return $this->display(__FILE__, 'table_all_product_wis3.tpl');

	}
}
