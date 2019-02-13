//加入队列错误时候触发
function fileQueueError_ll(file, errorCode, message) {
	try {
		var imageName = "error.gif";
		var errorName = "";
		if (errorCode === SWFUpload.errorCode_QUEUE_LIMIT_EXCEEDED) {
			errorName = "You have attempted to queue too many files.";
		}

		if (errorName !== "") {
			alert(errorName);
			return;
		}

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			imageName = "zerobyte.gif";
			break;
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			imageName = "toobig.gif";
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
		default:
			alert(message);
			break;
		}

		//addImage("images/" + imageName);

	} catch (ex) {
		this.debug(ex);
	}

}

function fileDialogComplete_ll(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadProgress_ll(file, bytesLoaded) {
	try {
		var percent = Math.ceil((bytesLoaded / file.size) * 100);

		//var progress = new FileProgress(file,  this.customSettings.upload_target);
		//progress.setProgress(percent);
		if (percent === 100) {
			//progress.setStatus("Creating thumbnail...");
			//progress.toggleCancel(false, this);
		} else {
			//progress.setStatus("Uploading...");
			//progress.toggleCancel(true, this);
		}
	} catch (ex) {
		this.debug(ex);
	}
}
//当文件上传的处理已经完成后触发
function uploadSuccess_ll(file, serverData) {
		var ro = eval("("+serverData+")");//转换为json对象 
		$('#imgupload_'+img_id).html("<a href='"+ro.savepath+ro.filename+"' target='_blank'><img width='80' height='80' src='"+ro.small_savepath+ro.filename+"' /></a><input type='hidden' name='small_"+img_name+"' value='"+ro.small_savepath+"' /><input type='hidden' name='filename_"+img_name+"' value='"+ro.filename+"' /><input type='hidden' name='big_"+img_name+"' value='"+ro.big_savepath+"' /><input type='hidden' name='save_"+img_name+"' value='"+ro.savepath+"' />");
}
//当上传队列中的一个文件完成了一个上传周期，无论是成功(uoloadSuccess触发)还是失败(uploadError触发)
function uploadComplete_ll(file) {
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			//var progress = new FileProgress(file,  this.customSettings.upload_target);
			//progress.setComplete();
			//progress.setStatus("All images received.");
			//progress.toggleCancel(false);
		}
	} catch (ex) {
		this.debug(ex);
	}
}
//无论什么时候，只要上传被终止或者没有成功完成，那么该事件都将被触发。
function uploadError_ll(file, errorCode, message) {
	var imageName =  "error.gif";
	var progress;
	try {
		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("Cancelled");
				progress.toggleCancel(false);
			}
			catch (ex1) {
				this.debug(ex1);
			}
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("Stopped");
				progress.toggleCancel(true);
			}
			catch (ex2) {
				this.debug(ex2);
			}
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			imageName = "uploadlimit.gif";
			break;
		default:
			alert(message);
			break;
		}

		addImage("images/" + imageName);

	} catch (ex3) {
		this.debug(ex3);
	}

}


function addImage_ll(src) {
	var newImg = document.createElement("img");
	newImg.style.margin = "5px";

	document.getElementById("thumbnails").appendChild(newImg);
	if (newImg.filters) {
		try {
			newImg.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 0;
		} catch (e) {
			// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
			newImg.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + 0 + ')';
		}
	} else {
		newImg.style.opacity = 0;
	}

	newImg.onload = function () {
		fadeIn(newImg, 0);
	};
	newImg.src = src;
}

function fadeIn_ll(element, opacity) {
	var reduceOpacityBy = 5;
	var rate = 30;	// 15 fps


	if (opacity < 100) {
		opacity += reduceOpacityBy;
		if (opacity > 100) {
			opacity = 100;
		}

		if (element.filters) {
			try {
				element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
			}
		} else {
			element.style.opacity = opacity / 100;
		}
	}

	if (opacity < 100) {
		setTimeout(function () {
			fadeIn(element, opacity);
		}, rate);
	}
}

