<?php
$helper = array(
	'mailer'=>true,
	'dynamic_search'=>true
);
$search_modules = array( //module => table
	'Pages'=>array(
						'allow'=>true,
						'table'=>'pages',
						'link'=>'pages/form.php?mode=edit&id=',
						'fields' => array('name','content'),
						'show'=> 'pages.id as id, pages.name as title, pages.content as description ' 
					),
	'Pages Categories'=>array(
						'allow'=>true,
						'table'=>'pages_categories',
						'link'=>'pages/categories-form.php?mode=edit&id=',
						'fields' => array('name','description'),
						'show'=> 'pages_categories.id as id, pages_categories.name as title, pages_categories.description as description ' 
					),
	'Blogs'=>array(
						'allow'=>true,
						'table'=>'blogs',
						'link'=>'blogs/form.php?mode=edit&id=',
						'fields' => array('name','content'),
						'show'=> ' blogs.id as id, blogs.name as title, blogs.content as description ' 
					),
	'Blogs Categories'=>array(
						'allow'=>true,
						'table'=>'blogs_categories',
						'link'=>'blogs/categories-form.php?mode=edit&id=',
						'fields' => array('name','description'),
						'show'=> ' blogs_categories.id as id , blogs_categories.name as title, blogs_categories.description as description ' 
					),
	'News'=>array(
						'allow'=>false,
						'table'=>'news',
						'link'=>'news/form.php?mode=edit&id=',
						'fields' => array('name','content'),
						'show'=> ' news.id as id , news.name as title, news.content as description ' 
					),
	'News Categories'=>array(
						'allow'=>false,
						'table'=>'news_categories',
						'link'=>'news/categories-form.php?mode=edit&id=',
						'fields' => array('name','description'),
						'show'=> 'news_categories.id as id, news_categories.name as title, news_categories.description as description ' 
					),
	'Products'=>array(
						'allow'=>false,
						'table'=>'products_mainproduct',
						'link'=>'products/form.php?mode=edit&id=',
						'fields' => array('name','content'),
						'show'=> ' products_mainproduct.id as id, products_mainproduct.name as title, products_mainproduct.content as description ' 
					),
	'Products Categories'=>array(
						'allow'=>false,
						'table'=>'products_categories',
						'link'=>'products/categories-form.php?mode=edit&id=',
						'fields' => array('name','description'),
						'show'=> ' products_categories.id as id , products_categories.name as title, products_categories.description as description ' 
					),
	'tours'=>array(
						'allow'=>true,
						'table'=>'tours',
						'link'=>'tours/form.php?mode=edit&id=',
						'fields' => array('name','content'),
						'show'=> ' tours.id as id , tours.name as title, tours.content as description ' 
					),
	'Tours Categories'=>array(
						'allow'=>true,
						'table'=>'tours_categories',
						'link'=>'tours/categories-form.php?mode=edit&id=',
						'fields' => array('name','description'),
						'show'=> ' tours_categories.id as id , tours_categories.name as title, tours_categories.description as description ' 
					),
		'Restaurants'=>array(
						'allow'=>false,
						'table'=>'restaurants',
						'link'=>'restaurants/form.php?mode=edit&id=',
						'fields' => array('name','content'),
						'show'=> ' restaurants.id as id , restaurants.name as title, restaurants.content as description ' 
					),
		'Restaurants Categories'=>array(
							'allow'=>false,
							'table'=>'restaurants_categories',
							'link'=>'restaurants/categories-form.php?mode=edit&id=',
							'fields' => array('name','description'),
							'show'=> ' restaurants_categories.id as id , restaurants_categories.name as title, restaurants_categories.description as description ' 
						),
			'Users'=>array(
							'allow'=>true,
							'table'=>'users',
							'link'=>'users/form.php?mode=edit&id=',
							'fields' => array('email','username','name','display_name'),
							'show'=> ' users.id as id , users.username as title, users.name as description ' 
						),
			'Users Categories'=>array(
								'allow'=>true,
								'table'=>'users_categories',
								'link'=>'users/categories-form.php?mode=edit&id=',
								'fields' => array('name','description'),
								'show'=> ' users_categories.id as id , users_categories.name as title, users_categories.description as description ' 
							),
			'Galleries'=>array(
								'allow'=>false,
								'table'=>'galleries',
								'link'=>'gallereis/galleries-form.php?mode=edit&id=',
								'fields' => array('name'),
								'show'=> ' galleries.id as id , galleries.name as title, galleries.name as description ' 
							),
			'Comments'=>array(
								'allow'=>false,
								'table'=>'comments',
								'link'=>'comments/form.php?mode=edit&id=',
								'fields' => array('content'),
								'show'=> ' comments.id as id , comments.content as title, comments.content as description ' 
							),
			'Contacts'=>array(
							'allow'=>true,
							'table'=>'contacts',
							'link'=>'contacts/form.php?mode=edit&id=',
							'fields' => array('from_name','from_email','content'),
							'show'=> ' contacts.id as id , contacts.from_name as title, contacts.content as description ' 
						),
				'Contacts Categories'=>array(
								'allow'=>true,
								'table'=>'contacts_categories',
								'link'=>'contacts/categories-form.php?mode=edit&id=',
								'fields' => array('name','description'),
								'show'=> ' contacts_categories.id as id , contacts_categories.name as title, contacts_categories.description as description ' 
				)
);
?>