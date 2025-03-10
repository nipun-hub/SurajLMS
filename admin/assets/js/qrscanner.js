// script.js file 

function domReady(fn) { 
	if ( 
		document.readyState === "complete" || 
		document.readyState === "interactive"
	) { 
		setTimeout(fn, 1000); 
	} else { 
		document.addEventListener("DOMContentLoaded", fn); 
	} 
} 

domReady(function () { 

	// If found you qr code 
	function onScanSuccess(decodeText, decodeResult) { 
		// console.log(decodeText);
		// alert("You Qr is : " + decodeText, decodeResult);
		qrRespons(decodeText);
	} 

	let htmlscanner = new Html5QrcodeScanner( 
		"my-qr-reader", 
		{ fps: 10, qrbos: 250 } 
	); 
	htmlscanner.render(onScanSuccess); 
});
