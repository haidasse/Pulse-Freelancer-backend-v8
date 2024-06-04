<?php  

class AccountImport extends MG_Import
{
	function __construct()
	{
		parent::__construct(); 
		
		$this->columns = [
			"name" => array (
				"title" => "Raison sociale" ,
				"description" => "La désignation du compte" ,
				"required" => true ,
			) ,
			"type" => array (
				"title" => "Type"  ,
				"description" => "Valeurs possibles: client, prospect, fournisseur, autre" ,
				"required" => true ,
			) ,
			"categoryid" => array (
				"title" => "Catégorie"  ,
				"description" => "Désignation de la catégorie correspondante pour les clients et les prospects" ,
			) ,
			"reference" => array (
				"title" => "Référence" ,
				"description" => "Référence du compte" ,
			) , 
			"phone" => array (
				"title" => "Téléphone" , 
				"description" => "" ,
			) ,
			"mobile" => array (
				"title" =>  "Portable" , 
				"description" => "" ,
			) ,
			"fax" => array (
				"title" => "Fax" , 
				"description" => "" ,
			) ,
			"website" => array (
				"title" => "Site Web" , 
				"description" => "Exemple: http://www.manageo.ma" ,
			) ,
			"email" => array (
				"title" => "Email" , 
				"description" => "Exemple: contact@manageo.ma" ,
			) ,
			"ice" => array (
				"title" => "ICE" , 
				"description" => "Identifiant Commun de l'Entreprise " ,
			) ,
			"currency" => array (
				"title" => "Devise" , 
				"description" => "Devise du compte, par défaut ".org_current('currency') ,
			) ,
			"billing_address" => array (
				"title" => "Adresse" , 
				"description" => "" ,
			) ,
			"billing_zipcode" => array (
				"title" => "Code postal" ,
				"description" => "" ,
			) , 
			"billing_city" => array (
				"title" => "Ville" , 
				"description" => "" ,
			) ,
			"billing_country" => array (
				"title" => "Pays" , 
				"description" => "" ,
			) ,
		] ; 
	}
	public function map() 
	{		
		/******************* types & countries & currencies ********************/
		$types = account_type_list() ;
		$countries = countries() ;
		$currencies = currencies() ;
		
		/******************* categories  ********************/
			
		$categories = account_category_list() ; 

		foreach ($categories as $key => $value) 
			$categories[$key] = slugify($value['title']) ;
			
		$categories = array_flip($categories) ; 
		
		/******************* accounts  ********************/
		
		$sql = "SELECT name, reference FROM account 
				WHERE org = ?
				AND status != 'deleted'" ; 
		$params = [
			"org" => org_current('id')
		] ;
		
		$accounts = db_select_all($sql, $params) ; 
		
		$names = [] ; 
		$references = [] ; 
		
		foreach ($accounts as $key => $value)
		{
			$names[] = slugify($value['name']) ; 
			$references[] = slugify($value['reference']) ; 
		}
		
		// dump($types) ;
		// dump($categories) ;
		// dump($names) ;
		// dump($references) ;
		// dump($countries) ;
		// dump($currencies) ;
			
		/******************* map columns  ********************/
		
		foreach ($this->lines as $key => $line)
		{
			foreach ($line as $column => $value)
			{
				switch($column)
				{
					case "name" : 
					
					$name = slugify($value) ; 
									
					if (in_array($name, $names))
					{
						$line['errors'][] = "raison sociale existante : ".$value ;
					}
					
					break ;
					case "reference" : 
					
					$reference = slugify($value) ; 
									
					if (in_array($reference, $references))
					{
						$line['errors'][] = "référence existante : ".$value ;
					}
					
					break ;
					case "type" : 
					
					$type = strtolower($value) ; 
					
					switch ($type)
					{
						case "client" : $type = "customer" ; break ;
						case "prospect" : $type = "prospect" ; break ;
						case "fournisseur" : $type = "supplier" ; break ;
						case "autre" : $type = "other" ; break ;
					}
					
					if (in_array($type, array_keys($types)))
					{
						$line['type'] = $type ; 
					}
					elseif (!empty($value))
					{
						$line['errors'][] = "type invalide : ".$value ;
					}
						
					break ; 
					case "categoryid" :
	
						$category = slugify($value) ; 
						
						if (in_array($category, array_keys($categories)))
						{
							$line['categoryid'] = $categories[$category] ; 
						}
						elseif (!empty($value))
						{
							$line['errors'][] = "catégorie introuvale : ".$value ;
						}
						
					break ;
					case "currency" :
	
						$currency = strtoupper($value) ; 
						
						if (in_array($currency, array_keys($currencies)))
						{
							$line['currency'] = $currency ; 
						}
						elseif (!empty($value))
						{
							$line['errors'][] = "devise introuvale : ".$value ;
						}
						
					break ;
					case "billing_country" :
	
						if (in_array($value, $countries))
						{
							$line['billing_country'] = $value ; 
						}
						elseif (!empty($value))
						{
							$line['errors'][] = "pays introuvale : ".$value ;
						}
						
					break ;
				}
			}
				
			$this->lines[$key] = $line ;
		}
	}
	public function process()
	{
		// dump($this->lines) ;
		
		$accounts = [] ;
			
		$account  = [
			"org" => org_current('id'),
			"userid" => user_current('id'),
			"owner" => user_current('id'),
			"professional" => "Y" ,
			"name" => "" ,
			"reference" => "" ,
			"type" => "" ,
			"categoryid" => 0 ,
			"phone" => "" ,
			"mobile" => "" ,
			"fax" => "" ,
			"website" => "" ,
			"email" => "" ,
			"ice" => "" ,
			"currency" => org_current('currency') ,
			"billing_address" => "" ,
			"billing_zipcode" => "" ,
			"billing_city" => "" ,
			"billing_country" => org_current('country') ,
			"created" => "NOW()" ,
			"status" => "active" ,
		] ;
		
		foreach ($this->lines as $key => $value)
		{
			$accounts[$key] = $account ;
			
			foreach ($account as $key2 => $value2)
			{
				if (isset($value[$key2]) && !empty($value[$key2]))
					$accounts[$key][$key2] = $value[$key2] ;
			}
		}
		
		// dump($accounts) ; 
		
		foreach($accounts as $key => $value)
		{
			if ($id = account_create($value))
			{
				$this->elements[] = $id ;
			}
		}
	} 
} 