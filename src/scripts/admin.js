/**
 * Run scripts on document ready
 * No jQuery here sorry
 */
document.addEventListener("DOMContentLoaded", () => {
	const reSendEmailLicense = document.querySelector("#license-resend");

	if (reSendEmailLicense) {
		reSendEmailLicense.addEventListener("click", (event) => {
			event.preventDefault();
			const postId = certify.post_id;
			const xhr = new XMLHttpRequest();
			const button = event.target;

			button.disabled = true;

			xhr.open("POST", certify.ajax_url, true);
			xhr.setRequestHeader(
				"Content-Type",
				"application/x-www-form-urlencoded",
			);

			xhr.onreadystatechange = function () {
				if (xhr.readyState === 4 && xhr.status === 200) {
					button.innerText = "Sent!";
				} else if (xhr.readyState === 4) {
					button.disabled = false;
				}
			};

			const params = `action=resend_email_license&post_id=${postId}&nonce=${certify.nonce}`;
			xhr.send(params);
		});
	}
});
