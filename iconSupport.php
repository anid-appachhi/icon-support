<?php

	/*function getSvgFromDrawable() {
		if(folderContainsFile( $folder $file ) ) {
			// command
		}
	}*/
	//folderContainsFile( "/home/ubuntu/Desktop/icon-support/res/drawable-v24","ic_launcher_foreground.xml" );
	//getAllDrawableFolder( "/home/ubuntu/Desktop/icon-support/res" );
	mergeForegroundWithBackground( "/var/www/REPO/ai_assis/res/drawable-v24/ic_launcher_foreground.xml", "/home/ubuntu/Desktop/icon-support/res/drawable-v24/ic_launcher_foreground.xml" );
	
	function folderContainsFile( $searchFolder, $searchFile ) {
		$folderContents = scandir( "$searchFolder" );
		foreach ( $folderContents as $file ) {
			$file = trim( $file );
			if( $file === $searchFile ) {
				return true;
			}
		}
		return false;
	}

	function getAllDrawableFolder( $sourceFolder ) {
		$drawableFolders = preg_grep( "/^drawable|(-[\w+])$/", scandir( $sourceFolder ) );
		foreach ( $drawableFolders as $folder ) {
			if( folderContainsFile( $sourceFolder."/".$folder, "ic_launcher_foreground.xml" ) ) {
				echo "got it \n";
				return;
			}
		}
		echo "did not get it \n";
	}

	function mergeForegroundWithBackground( $backgroundFile, $foregroundFile ) {
		$filePtrBackgroundFile = fopen( $backgroundFile, 'r' );
		$foregroundFileContents = file_get_contents( $foregroundFile );
		if( $foregroundFileContents === false ) {
			//log
			echo "did not open \n";
		}
		$firstPathTagPos = strpos( $foregroundFileContents, "<path" );
		$endVectorTagPos = strpos( $foregroundFileContents, "</vector>" );
		$pathTagsFromForegroundFile = substr( $foregroundFileContents, $firstPathTagPos,  $endVectorTagPos - $firstPathTagPos );

		$imageFile = "fullImage.xml";
		$filePtrImageFile = fopen( $imageFile, 'w+');
		$arrayFile = array();
		while( ( $line = fgets( $filePtrBackgroundFile ) ) !== false ) {
			if( trim( $line ) != "</vector>" ) {
			 fputs( $filePtrImageFile, $line );
			 array_push( $arrayFile, $line );
			} else {
				fputs( $filePtrImageFile, $pathTagsFromForegroundFile );
				fputs( $filePtrImageFile, $line );
			}
			
		}

	}
?>