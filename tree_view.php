<?php
	$path = urldecode( $_REQUEST['path'] );
	$files = array();
	$folder = "";

	if( file_exists( $path)) {
		if( $path[ strlen( $path ) - 1 ] ==  '/' )
			$folder = $path;
		else
			$folder = $path . '/';
		
		$allFolders = @opendir( $path );
		while(( $file = @readdir( $allFolders ) ) != false )
			$files[] = $file;
		@closedir( $allFolders );
	}

	if( count( $files ) > 2 ) { 
		natcasesort( $files );
		$list = '<ul class="filetree" style="display: none;">';
		foreach( $files as $file ) {
			if( file_exists( $folder . $file ) && $file != '.' && $file != '..'  && is_dir( $folder . $file )) {
				$list .= '<li class="folder collapsed"><a class="item" href="#" rel="' . $folder .  $file . '" data-name="' . $file . '">' . $file . '</a></li>';
			}
		}
		foreach( $files as $file ) {
			if( file_exists( $folder . $file ) && $file != '.' && $file != '..' && !is_dir( $folder . $file )) {
				$ext = preg_replace('/^.*\./', '', $file);
				$list .= '<li class="file ext_' . $ext . '"><a href="#" rel="'  . $folder .  $file . '">' . $file . '</a></li>';
			}
		}
		$list .= '</ul>';			
	}

	echo @$list;