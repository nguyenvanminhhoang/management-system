<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Management System</title>
	<link rel="stylesheet" href="css/style.css" type="text/css" >
	<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body oncontextmenu="return false;">
	<div class="sidenav">
		<div id="logo">
			<h1 align="center">MANAGEMENT SYSTEM</h1>
		</div>
		<div id="container"></div>
	</div>
	<div id="main" class="main">
		<div align="right">
			<button type="button" name="create_folder" id="create_folder" class="btn btn-success">Create Folder</button>
			<button type="button" name="create_file" id="create_file" class="btn btn-success">Create File</button>
		</div>
		<br />
		<!-- List folder -->
		<div id="folder_table" class="table-responsive"></div>
	</div>    
</body>
</html>
<!-- Menu context -->
<div id="context-menu">
  <div class="item-context-menu">
    <a id="rename" class="item-menu" href="#">Rename</a> 
  </div>
  <div class="item-context-menu">
		<a id="copy" class="item-menu" href="#">Copy</a>
  </div>
  <div class="item-context-menu">
		<a id="paste" class="item-menu" href="#">Paste</a>
  </div>
  <div class="item-context-menu">
		<a id="delete" class="item-menu" href="#">Delete</a>
  </div>
</div>

<!-- Create Folder Modal -->
<div id="folderModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
          <span id="change_title">Create Folder</span>
        </h4>
      </div>
      <div class="modal-body">
        <p>
          Enter Folder Name
          <input type="text" name="folder_name" id="folder_name" class="form-control" />
        </p>
        <input type="hidden" name="action" id="action" />
        <input type="hidden" name="old_name" id="old_name" />
        <input type="button" name="folder_button" id="folder_button" class="btn btn-info" value="Create" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Rename Folder/File Modal -->
<div id="renameModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
          <span id="change_title">Rename</span>
        </h4>
      </div>
      <div class="modal-body">
        <p>
          Enter Name
          <input type="text" name="name" id="name" class="form-control" />
        </p>
        <input type="hidden" name="action" id="action" />
        <input type="hidden" name="old_name" id="old_name" />
        <input type="button" name="rename_button" id="rename_button" class="btn btn-info" value="Rename" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Create File Modal -->
<div id="fileModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
          <span id="change_title">Create File</span>
        </h4>
      </div>
      <div class="modal-body">
        <p>
          Enter File Name
          <input type="text" name="file_name" id="file_name" class="form-control" />
				</p>
				<p>
          Enter Content
					<textarea class="form-control" rows="4" cols="50" name="file_content" id="file_content" placeholder="Enter text here..."></textarea>
        </p>
        <input type="hidden" name="action" id="action" />
				<input type="hidden" name="old_name" id="old_name" />
				<input type="hidden" name="old_content" id="old_content" />
        <input type="button" name="file_button" id="file_button" class="btn btn-info" value="Create" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" >
    $(document).ready( function() {
			var type = "";

			getfilelist($('#container'), "D:/");

      function getfilelist( cont, path ) {
				$.ajax({
					url: "tree_view.php",
					method: "POST",
					data: {path:path},
					success: function (data) {
						cont.append(data);
						if( '.' === path ) 
							$( cont ).find('UL:hidden').show();
						else 
							$( cont ).find('UL:hidden').slideDown({ duration: 500, easing: null });
					},
				});
			}

			$(document).on("click", 'LI A', function () {
				var entry = $(this).parent();
				if( entry.hasClass('folder') ) {
					if( entry.hasClass('collapsed') ) {							
						entry.find('UL').remove();
						getfilelist(entry, escape( $(this).attr('rel')));
						entry.removeClass('collapsed').addClass('expanded');
					}
					else {
						entry.find('UL').slideUp({ duration: 500, easing: null });
						entry.removeClass('expanded').addClass('collapsed');
					}
				}
				return false;
			});

			// Load folder and file in container
			function load_folder_list(path) {				
				var action = "fetch";
				$.ajax({
					url: "action.php",
					method: "POST",
					data: { action: action, path:path },
					success: function (data) {
						$("#folder_table").html(data);
					},
      	});
   		}

			 // Load folder and file in container when click in item of container
			$(document).on("click", '.item', function () {
				var path = $(this).attr('rel');
				$('#main').attr('rel', path);
				load_folder_list(path);
			});

			// Create folder: Show Folder Modal
			$(document).on("click", "#create_folder", function () {
				$("#action").val("create");
				$("#folder_name").val("");
				$("#folder_button").val("Create");
				$("#old_name").val("");
				$("#change_title").text("Create Folder");
				$("#folderModal").modal("show");
			});

			// Handle Create Folder
			$(document).on("click", "#folder_button", function () {
				var path = $("#main").attr('rel');
				var folder_name = $("#folder_name").val();
				var action = $("#action").val();
				var old_name = $("#old_name").val();
				
				if (folder_name != "") {
					$.ajax({
						url: "action.php",
						method: "POST",
						data: { folder_name: folder_name, action: action, old_name: old_name, path:path },
						success: function (data) {
							$("#folderModal").modal("hide");
							load_folder_list(path);
							alert(data);
						},
					});
				} else {
					alert("Enter Folder Name");
				}
			});

			// Show context menu
			$(document).on("contextmenu", '#item', function(e) {
				event.preventDefault();
				var type = $(this).data("type");
				$('.item-menu').attr('data-type', type);
				var name = $(this).data("name");
				$('.item-menu').attr('rel', name);
				var contextElement = document.getElementById("context-menu");
				contextElement.style.top = event.clientY + "px";
				contextElement.style.left = event.clientX + "px";
				contextElement.classList.add("active");				
			});

			// Hidden context menu
			$(document).on("click",function(){
				document.getElementById("context-menu").classList.remove("active");
			});

			// Show Create File Modal
			$(document).on("click", "#create_file", function () {
				$("#action").val("create_file");
				$("#file_name").val("");
				$("#file_button").val("Create");
				$("#old_name").val("");
				$("#file_content").val("");
				$("#old_content").val("");
				$("#change_title").text("Create File");
				$("#fileModal").modal("show");
			});

			// Handle Create File
			$(document).on("click", "#file_button", function () {
				var path = $("#main").attr('rel');
				var file_name = $("#file_name").val();
				var file_content = $("#file_content").val();
				var action = $("#action").val();
				var old_name = $("#old_name").val();
				var old_content = $("#old_content").val();				
				if (folder_name != "" || file_content != "") {
					$.ajax({
						url: "action.php",
						method: "POST",
						data: { file_name: file_name, file_content:file_content, action: action, old_name: old_name, path:path, old_content:old_content },
						success: function (data) {
							$("#fileModal").modal("hide");
							load_folder_list(path);
							alert(data);
						},
					});
				} else {
					alert("Enter File Name And Content");
				}
			});
			// Show popup rename
			$(document).on("click", "#rename", function () {			
				var name = $(this).attr('rel');
				$("#old_name").val(name);
				$("#name").val(name);
				$("#action").val("rename");
				$("#rename_button").val("Rename");
				$("#change_title").text("Rename");
				$("#renameModal").modal("show");
			});

			// Handle rename
			$(document).on("click", "#rename_button", function () {			
				var path = $("#main").attr('rel');
				var name = $("#name").val();
				var action = $("#action").val();
				var old_name = $("#old_name").val();
				if (name != "") {
					$.ajax({
						url: "action.php",
						method: "POST",
						data: { name: name, action: action, old_name: old_name, path:path },
						success: function (data) {
							$("#renameModal").modal("hide");
							load_folder_list(path);
							alert(data);
						},
					});
				} else {
					alert("Enter New Name");
				}
			});

			$(document).on("click", "#delete", function () {
				var path = $("#main").attr('rel');
				var name = $(this).attr('rel');
				type = $("#item").data('type');
				if (type === "file") {
					var action = "delete_file";
				} else if(type === "folder") {
					var action = "delete_folder";
				}
				if (confirm("Are you sure you want to delete it?")) {
					$.ajax({
						url: "action.php",
						method: "POST",
						data: { name: name, action: action, path:path },
						success: function (data) {
							alert(data);
							load_folder_list(path);
						},
					});
				} else {					
					load_folder_list(path);
					type = "";
				}
			});
    });
</script>