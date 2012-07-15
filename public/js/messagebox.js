
/**
 * Show a message box to the user
 * @param {String} message Message to display
 * @return void
 */
function messageBox(message) {
	if (message === undefined) {
		message = "Server error, please try again";
	}
    bootbox.alert(message);
}
