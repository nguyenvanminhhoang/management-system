<?php
	$path = urldecode($_REQUEST['path']);
	$files = [];
	$folder = "";

	if (isset($_POST["action"])) {
		if ($_POST["action"] == 'fetch') {
			if (file_exists($path)) {
				if ($path[strlen($path) - 1] == '/') {
					$folder = $path;
				} else {
					$folder = $path . '/';
				}

				$allFolders = @opendir($path);
				while (($file = @readdir($allFolders)) != false) {
					$files[] = $file;
				}
				@closedir($allFolders);
			}

			if (count($files) > 2) {
				natcasesort($files);
				$list = '<ul class="filetree">';
				foreach ($files as $file) {
					if (file_exists($folder . $file) && $file != '.' && $file != '..' && is_dir($folder . $file)) {
						$list .= '<li class="folder collapsed"><a id="item" class="item" data-type="folder" href="#" rel="' . $folder . $file . '" data-name="' . $file . '">' . $file . '</a></li>';
					}
				}
				foreach ($files as $file) {
					if (file_exists($folder . $file) && $file != '.' && $file != '..' && !is_dir($folder . $file)) {
						$ext = preg_replace('/^.*\./', '', $file);
						$list .= '<li class="file ext_' . $ext . '"><a id="item" class="file"  data-type="file" href="#" rel="' . $folder . $file . '" data-name="' . $file . '">' . $file . '</a></li>';
					}
				}
				$list .= '</ul>';
			}
			echo @$list;
		}
	}

	if ($_POST["action"] == "create") {
		if (!file_exists($_POST["path"] . "/" . $_POST["folder_name"])) {
			mkdir($_POST["path"] . "/" . $_POST["folder_name"], 0777, true);
			echo "Folder Created";
		} else {
			echo "Folder Already Created";
		}
	}

	if ($_POST["action"] == "rename") {
		if (!file_exists($_POST["path"] . "/" . $_POST["name"])) {
			rename($_POST["path"] . "/" . $_POST["old_name"], $_POST["path"] . "/" . $_POST["name"]);
			echo "Successfully";
		} else {
			echo "File/Folder Already Created";
		}
	}

	if ($_POST["action"] == "create_file") {
		if (!file_exists($_POST["path"] . "/" . $_POST["file_name"] . 'txt')) {
			$myfile = fopen($_POST["path"] . "/" . $_POST["file_name"] . ".txt", "w") or die("Unable to open file!");
			$content = $_POST["file_content"];
			fwrite($myfile, $content);
			fclose($myfile);
			echo "File Craeted";
		} else {
			echo "File Already Created";
		}
	}

	if ($_POST["action"] == "delete_folder") {
		$files = scandir($_POST["path"] . "/" . $_POST["name"]);
		foreach ($files as $file) {
		  if ($file == "." || $file == "..") {
			continue;
		  } else {
			unlink($_POST["path"] . "/" . $_POST["name"] . "/" . $file);
		  }
		}
		if (rmdir($_POST["path"] . "/" . $_POST["name"])) {
		  echo "Folder Deleted";
		}
	}

	if ($_POST["action"] == "delete_file") {
    if (file_exists($_POST["path"] . "/" . $_POST["name"])) {
      unlink($_POST["path"] . "/" . $_POST["name"]);
      echo "File Deleted";
    }
  }

	
