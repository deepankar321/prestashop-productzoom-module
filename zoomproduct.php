<?php
//created at Iriemo Tech Pvt Ltd.
/*  The MIT License (MIT)

Copyright (c) 2016 Deepankar Manduri (mandurideepaankar@gmail.com)>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

if (!defined('_PS_VERSION_'))
  exit;

class ZoomProduct extends Module
{
	
	public function __construct()
	{
		$this->name = 'zoomproduct';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Deepankar Manduri';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	
		$this->bootstrap = true;
		parent::__construct();	//needed for translations

		$this->displayName = $this->l('ZoomProduct for Prestashop');
		$this->description = $this->l('Enables zooming on product images and magnifier effects.');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
		
	}

	public function install()
	{
		/* set default values when installing */

		if (!parent::install())
			return false;
		
			/* Default configuration values */
			Configuration::updateValue('ELEVATEZOOM_PRODUCT', 1) ;
			Configuration::updateValue('ELEVATEZOOM_ZOOM_TYPE', 'inner') ;
			Configuration::updateValue('ELEVATEZOOM_FADE_IN', 500) ;
			Configuration::updateValue('ELEVATEZOOM_FADE_OUT', 500) ;
			Configuration::updateValue('ELEVATEZOOM_WINDOW_POS', 1) ;
			Configuration::updateValue('ELEVATEZOOM_SCROLL', 'true') ;
			Configuration::updateValue('ELEVATEZOOM_EASING', 'true') ;
			Configuration::updateValue('ELEVATEZOOM_CURSOR_TYPE', 'crosshair') ;
			Configuration::updateValue('ELEVATEZOOM_TINT', 'false') ;
			Configuration::updateValue('ELEVATEZOOM_TINT_COLOR', '#333') ;
			Configuration::updateValue('ELEVATEZOOM_TINT_OPACITY', 0.4) ;
			Configuration::updateValue('ELEVATEZOOM_LENS_SHAPE', 'square') ;
			Configuration::updateValue('ELEVATEZOOM_LENS_SIZE', 150) ;
			Configuration::updateValue('ELEVATEZOOM_EXTRA_PARAMS', '') ;
			Configuration::updateValue('ELEVATEZOOM_OTHER', 0) ;
			Configuration::updateValue('ELEVATEZOOM_OTHER_CODE', '$("a.product_image img").elevateZoom({zoomType : "lens", lensShape : "round", lensSize : 200});') ;
											
			return $this->registerHook('header') && $this->registerHook('productfooter');	//install and register the module on header hook
	}
	
	public function uninstall()
	{
		if (!parent::uninstall() ||
			!Configuration::deleteByName('ELEVATEZOOM_PRODUCT') ||
			!Configuration::deleteByName('ELEVATEZOOM_ZOOM_TYPE') ||
			!Configuration::deleteByName('ELEVATEZOOM_FADE_IN') ||
			!Configuration::deleteByName('ELEVATEZOOM_FADE_OUT') ||
			!Configuration::deleteByName('ELEVATEZOOM_WINDOW_POS') ||
			!Configuration::deleteByName('ELEVATEZOOM_SCROLL') ||
			!Configuration::deleteByName('ELEVATEZOOM_EASING') ||
			!Configuration::deleteByName('ELEVATEZOOM_CURSOR_TYPE') ||
			!Configuration::deleteByName('ELEVATEZOOM_TINT') ||
			!Configuration::deleteByName('ELEVATEZOOM_TINT_COLOR') ||
			!Configuration::deleteByName('ELEVATEZOOM_TINT_OPACITY') ||
			!Configuration::deleteByName('ELEVATEZOOM_LENS_SHAPE') ||
			!Configuration::deleteByName('ELEVATEZOOM_LENS_SIZE') ||
			!Configuration::deleteByName('ELEVATEZOOM_EXTRA_PARAMS') ||
			!Configuration::deleteByName('ELEVATEZOOM_OTHER') ||
			!Configuration::deleteByName('ELEVATEZOOM_OTHER_CODE')
			)
			
			return false;
			
		return true;
	}	
	
	//show configure button in backend and process the form/s
	public function getContent()
	{
		$html = '';
		
		if (Tools::isSubmit('SubmitElevatezoom'))
		{
			//created by Iriemo
			
			
			Configuration::updateValue('ELEVATEZOOM_PRODUCT', (int)(Tools::getValue('ELEVATEZOOM_PRODUCT')));
			Configuration::updateValue('ELEVATEZOOM_ZOOM_TYPE',Tools::getValue('ELEVATEZOOM_ZOOM_TYPE')); 
			
			if ( (int)(Tools::getValue('zoom_fade_in')) < 0)
				$html .= '<div class="alert alert-danger">'.$this->l('Invalid zoom fade in value. It should be greater then 0').'</div>';
			else
				Configuration::updateValue('ELEVATEZOOM_FADE_IN',(int)(Tools::getValue('ELEVATEZOOM_FADE_IN')));
				
			if ((int)(Tools::getValue('zoom_fade_in')) < 0)
				$html .='<div class="alert alert-danger">'.$this->l('Invalid zoom fade out value. It should be greater then 0').'</div>';
			else
			Configuration::updateValue('ELEVATEZOOM_FADE_OUT', (int)(Tools::getValue('ELEVATEZOOM_FADE_OUT')));			
				
			if ((int)(Tools::getValue('ELEVATEZOOM_WINDOW_POS')) < 1 || (int)(Tools::getValue('ELEVATEZOOM_WINDOW_POS')) > 16)
				$html .= '<div class="alert alert-danger">'.$this->l('Invalid window position value. It should be between 1 to 16').'</div>';
			else
				Configuration::updateValue('ELEVATEZOOM_WINDOW_POS',(int)(Tools::getValue('ELEVATEZOOM_WINDOW_POS')));										
					
			Configuration::updateValue('ELEVATEZOOM_SCROLL', Tools::getValue('ELEVATEZOOM_SCROLL'));		
			Configuration::updateValue('ELEVATEZOOM_EASING', Tools::getValue('ELEVATEZOOM_EASING'));	
			Configuration::updateValue('ELEVATEZOOM_CURSOR_TYPE',Tools::getValue('ELEVATEZOOM_CURSOR_TYPE'));
			Configuration::updateValue('ELEVATEZOOM_TINT',Tools::getValue('ELEVATEZOOM_TINT'));			
			Configuration::updateValue('ELEVATEZOOM_TINT_COLOR',  Tools::getValue('ELEVATEZOOM_TINT_COLOR'));
			
			if (Tools::getValue('ELEVATEZOOM_TINT_OPACITY') < 0.0)
				$html .= '<div class="alert alert-danger">'.$this->l('Invalid tint opacity value. It should be greater then 0.0').'</div>';	
			else
				Configuration::updateValue('ELEVATEZOOM_TINT_OPACITY', Tools::getValue('ELEVATEZOOM_TINT_OPACITY')); 			
			
			Configuration::updateValue('ELEVATEZOOM_LENS_SHAPE',Tools::getValue('ELEVATEZOOM_LENS_SHAPE')); 
			
			if ((int)( Tools::getValue('ELEVATEZOOM_LENS_SIZE')) < 0)
				$html .= '<div class="alert alert-danger">'.$this->l('Invalid lens size value. It should be greater then 0 ').'</div>';
			else
				Configuration::updateValue('ELEVATEZOOM_LENS_SIZE', (int)( Tools::getValue('ELEVATEZOOM_LENS_SIZE')));				

			Configuration::updateValue('ELEVATEZOOM_EXTRA_PARAMS', Tools::getValue('ELEVATEZOOM_EXTRA_PARAMS')); 	

			/* other code for extra calls */
	
			Configuration::updateValue('ELEVATEZOOM_OTHER', (int)(Tools::getValue('ELEVATEZOOM_OTHER')));
			Configuration::updateValue('ELEVATEZOOM_OTHER_CODE', Tools::getValue('ELEVATEZOOM_OTHER_CODE'));
		
		 	$html .= $this->displayConfirmation($this->l('Settings updated'));	


		 }
		/* Configuration form */
		return $html.$this->displayForm();
	}

	//hooks module to header
	public function hookHeader($params)
	{
		$this->context->controller->addJQueryPlugin('elevatezoom');
	}

	public function hookProductFooter($params)
	{	
		 $this->smarty->assign(array(		
			'zoom_product' =>       (int)Configuration::get('ELEVATEZOOM_PRODUCT'),
			'zoom_type' =>			Configuration::get('ELEVATEZOOM_ZOOM_TYPE'),
			'zoom_fade_in' =>     	(int)(Configuration::get('ELEVATEZOOM_FADE_IN')),
			'zoom_fade_out' =>    	(int)(Configuration::get('ELEVATEZOOM_FADE_OUT')),
			'zoom_window_pos' =>    (int)(Configuration::get('ELEVATEZOOM_WINDOW_POS')),
			'zoom_scroll' =>    	Configuration::get('ELEVATEZOOM_SCROLL'),
			'zoom_easing' =>    	Configuration::get('ELEVATEZOOM_EASING'),
			'zoom_cursor_type' => 	Configuration::get('ELEVATEZOOM_CURSOR_TYPE'),
			'zoom_tint' => 			Configuration::get('ELEVATEZOOM_TINT'),
			'zoom_tint_color' => 	Configuration::get('ELEVATEZOOM_TINT_COLOR'),
			'zoom_tint_opacity' => 	Configuration::get('ELEVATEZOOM_TINT_OPACITY'),
			'zoom_lens_shape' => 	Configuration::get('ELEVATEZOOM_LENS_SHAPE'),
			'zoom_lens_size' =>     (int)(Configuration::get('ELEVATEZOOM_LENS_SIZE')),
			'zoom_extra_params'=>   Configuration::get('ELEVATEZOOM_EXTRA_PARAMS'),
			'zoom_other' =>       	(int)Configuration::get('ELEVATEZOOM_OTHER'),
			'zoom_other_code' =>	Configuration::get('ELEVATEZOOM_OTHER_CODE')		
		));						 
		
		return $this->display(__FILE__, 'zoomproduct.tpl');			
	}

	public function displayForm()
	{
		
	/* some constants from css and elevateZoom plugin */
		$zoomType = array(
              array(
                'ELEVATEZOOM_ZOOM_TYPE' => 'inner', 
                'name' => 'inner' 
              ),
              array(
                'ELEVATEZOOM_ZOOM_TYPE' => 'lens',
                'name' => 'lens'
              ),
              array(
                'ELEVATEZOOM_ZOOM_TYPE' => 'window',
                'name' => 'window'
              )
		);
	
		$cursors = array(
              array(
                'ELEVATEZOOM_CURSOR_TYPE' => 'auto', 
                'name' => 'auto' 
              ),
              array(
                'ELEVATEZOOM_CURSOR_TYPE' => 'default',
                'name' => 'default'
              ),
              array(
                'ELEVATEZOOM_CURSOR_TYPE' => 'crosshair',
                'name' => 'crosshair'
              ),
              array(
                'ELEVATEZOOM_CURSOR_TYPE' => 'pointer',
                'name' => 'pointer'
              ),
              array(
                'ELEVATEZOOM_CURSOR_TYPE' => '-moz-zoom-in',
                'name' => '-moz-zoom-in'
              )
    	);
	
		$lensShape = array(
			array(
                'ELEVATEZOOM_CURSOR_TYPE' => 'square', 
                'name' => 'square' 
              ),
              array(
                'ELEVATEZOOM_CURSOR_TYPE' => 'round',
                'name' => 'round'
              )
		);
		
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Zooming Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'desc'=>$this->displayConfirmation($this->l('Note: Please remember that this module requires to turn off "JqZoom" in Preferences > Products to work properly.'))
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Activate Zoomin Effect for product page'),
						'desc' => $this->l('Set if elevateZoom is active in product page with the parameters shown below.'),
						'name' => 'ELEVATEZOOM_PRODUCT',
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),

					 array(
					 	'type' => 'select',
					 	'lang' => true,
					 	'label' => $this->l('Zoom Type'),
					 	'name' => 'ELEVATEZOOM_ZOOM_TYPE',
					 	'desc' => $this->l('Please Eneter Web Site URL Address.'),
					 	'options' => array(
					 	'default' => array('value' => 0, 'label' => $this->l('Choose Zoom Type')),
					 		'query' => $zoomType,
					 		'id' => 'ELEVATEZOOM_ZOOM_TYPE', 
					 		'name' => 'name'
							)
						),
					 
					array(
						'type' => 'select',
						'label' => $this->l('Lens Shape :'),
						'desc' => $this->l('Choose a lens shape in case the zoom mode is lens.'),
						'name' => 'ELEVATEZOOM_LENS_SHAPE',
						'options' => array(
							'default' => array('value' => 0, 'label' => $this->l('Choose Lens Shape')),
							'query' => $lensShape,
							'id' => 'ELEVATEZOOM_LENS_SHAPE',
							'name' => 'name'
						),
					),
					
					array(
						'type' => 'text',
						'label' => $this->l('Fade in delay'),
						'desc' => $this->l('Set the fade in delay in milliseconds.'),
						'name' => 'ELEVATEZOOM_FADE_IN',
						'suffix' => $this->l('milliseconds'),
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),
					
					array(
						'type' => 'text',
						'label' => $this->l('Fade out delay'),
						'desc' => $this->l('Set the fade out delay in milliseconds.'),
						'name' => 'ELEVATEZOOM_FADE_OUT',
						'suffix' => $this->l('milliseconds'),
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),
					


					array(
						'type' => 'switch',
						'label' => $this->l('Use Easing'),
						'desc' => $this->l('Set if easing is used or not.'),
						'name' => 'ELEVATEZOOM_EASING',
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),
					
					array(
						'type' => 'switch',
						'label' => $this->l('Scroll with mousewheel'),
						'desc' => $this->l('Set if scrolling in and out with the mousewheel is allowed.'),
						'name' => 'ELEVATEZOOM_SCROLL',
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),
					
					array(
						'type' => 'select',
						'label' => $this->l('Cursor Type :'),
						'desc' => $this->l('Choose the cursor icon that will be displayed over the image.'),
						'name' => 'ELEVATEZOOM_CURSOR_TYPE',
						'options' => array(
							'default' => array('value' => 0, 'label' => $this->l('Choose Cursor Type')),
							'query' => $cursors,
							'id' => 'ELEVATEZOOM_CURSOR_TYPE',
							'name' => 'name'
						),
					),
					
					array(
						'type' => 'switch',
						'label' => $this->l('Use Tint'),
						'desc' => $this->l('Set if the image will be tinted when zooming.'),
						'name' => 'ELEVATEZOOM_TINT',
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Tint color'),
						'desc' => $this->l('Set the color of the tint in case tinting is active. Can be any valid css color: red, #ccc, rgb(0,0,0), etc.'),
						'name' => 'ELEVATEZOOM_TINT_COLOR',
						'suffix' => $this->l('COLOR'),
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Tint opacity'),
						'desc' => $this->l('Set the tint opacity percentage in case tinting is active. Must be float between 0.0 and 1.0.'),
						'name' => 'ELEVATEZOOM_TINT_OPACITY',
						'suffix' => $this->l('OPACITY'),
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),

					array(
						'type' => 'text',
						'label' => $this->l('Lens Size'),
						'desc' => $this->l('Set the lens size in pixels in case the zoom mode is lens.'),
						'name' => 'ELEVATEZOOM_LENS_SIZE',
						'suffix' => $this->l('Pixels'),
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Extra parameters'),
						'desc' => $this->l('Put any extra option parameters that you want for the elevateZoom jQuery plugin, comma-separated.').'<br>'.
							$this->l('Check the elevateZoom homepage (see Credits) for details about other parameters or look at the jquery.elevatezoom.js file.').'<br>'.
							$this->l('Example: zoomWindowWidth:300, zoomWindowHeight:100.'),
						'name' => 'ELEVATEZOOM_EXTRA_PARAMS',
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Activate elevateZoom additional code'),
						'desc' => $this->l('Set if additional code to apply elevateZoom will be executed.'),
						'name' => 'ELEVATEZOOM_OTHER',
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),
					
					array(
						'type' => 'text',
						'label' => $this->l('Additional code'),
						'desc' => $this->l('Put here any additional JavaScript or jQuery code to apply elevateZoom jQuery plugin.').'<br>'.
							$this->l('Example to apply to product lists: $("a.product_image img").elevateZoom({zoomType : "lens", lensShape : "round", lensSize : 200});'),
						'name' => 'ELEVATEZOOM_OTHER_CODE',
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
					)
			),
	);
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'SubmitElevatezoom';
		//$helper->submit_action = 'submitElevatezoomExtra';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		 );
 
		 return $helper->generateForm(array($fields_form));
		 

	}
//created at Iriemo
	public function getConfigFieldsValues()
	{
		return array(
			'ELEVATEZOOM_PRODUCT' => Tools::getValue('ELEVATEZOOM_PRODUCT', Configuration::get('ELEVATEZOOM_PRODUCT')),
			'ELEVATEZOOM_ZOOM_TYPE' => Tools::getValue('ELEVATEZOOM_ZOOM_TYPE', Configuration::get('ELEVATEZOOM_ZOOM_TYPE')),
			'ELEVATEZOOM_FADE_IN' => Tools::getValue('ELEVATEZOOM_FADE_IN', Configuration::get('ELEVATEZOOM_FADE_IN')),
			'ELEVATEZOOM_FADE_OUT' => Tools::getValue('ELEVATEZOOM_FADE_OUT', Configuration::get('ELEVATEZOOM_FADE_OUT')),
			'ELEVATEZOOM_WINDOW_POS' => Tools::getValue('ELEVATEZOOM_WINDOW_POS', Configuration::get('ELEVATEZOOM_WINDOW_POS')),
			'ELEVATEZOOM_SCROLL' => Tools::getValue('ELEVATEZOOM_SCROLL', Configuration::get('ELEVATEZOOM_SCROLL')),
			'ELEVATEZOOM_EASING' => Tools::getValue('ELEVATEZOOM_EASING', Configuration::get('ELEVATEZOOM_EASING')),
			'ELEVATEZOOM_CURSOR_TYPE' => Tools::getValue('ELEVATEZOOM_CURSOR_TYPE', Configuration::get('ELEVATEZOOM_CURSOR_TYPE')),
			'ELEVATEZOOM_TINT' => Tools::getValue('ELEVATEZOOM_TINT', Configuration::get('ELEVATEZOOM_TINT')),
			'ELEVATEZOOM_TINT_COLOR' => Tools::getValue('ELEVATEZOOM_TINT_COLOR', Configuration::get('ELEVATEZOOM_TINT_COLOR')),
			'ELEVATEZOOM_TINT_OPACITY' => Tools::getValue('ELEVATEZOOM_TINT_OPACITY', Configuration::get('ELEVATEZOOM_TINT_OPACITY')),
			'ELEVATEZOOM_LENS_SHAPE' => Tools::getValue('ELEVATEZOOM_LENS_SHAPE', Configuration::get('ELEVATEZOOM_LENS_SHAPE')),
			'ELEVATEZOOM_LENS_SIZE' => Tools::getValue('ELEVATEZOOM_LENS_SIZE', Configuration::get('ELEVATEZOOM_LENS_SIZE')),
			'ELEVATEZOOM_EXTRA_PARAMS' => Tools::getValue('ELEVATEZOOM_EXTRA_PARAMS', Configuration::get('ELEVATEZOOM_EXTRA_PARAMS')),
			'ELEVATEZOOM_OTHER' => Tools::getValue('ELEVATEZOOM_OTHER', Configuration::get('ELEVATEZOOM_OTHER')),
			'ELEVATEZOOM_OTHER_CODE' => Tools::getValue('ELEVATEZOOM_OTHER_CODE', Configuration::get('ELEVATEZOOM_OTHER_CODE')),
		);
	}


}
