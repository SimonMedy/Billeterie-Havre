function setupDownloadPdfButton(eventId, eventName, eventDate) {
    console.log('SetupDownloadPdfButton called with:', eventId, eventName, eventDate);
    const buttonId = `download-pdf-${eventId}`;
    const downloadButton = document.getElementById(buttonId);
    console.log('downloadButton:', downloadButton);

    if (downloadButton) {
        downloadButton.addEventListener('click', function() {
            console.log('Button clicked');
            const qrElement = document.getElementById('qrcode');
            console.log('qrElement:', qrElement);

            if (qrElement) {
                const qrCode = new QRCode(qrElement, {
                    text: 'Event ID: ' + eventId,
                    width: 100,
                    height: 100,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
                console.log('QRCode created');

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                console.log('jsPDF instance created');

                doc.text(eventName, 10, 10);
                doc.text('Date: ' + eventDate, 10, 20);
                doc.text('Event ID: ' + eventId, 10, 30);

                const qrcodeCanvas = qrElement.querySelector('canvas');
                if (qrcodeCanvas) {
                    const qrcodeImgData = qrcodeCanvas.toDataURL('image/png');
                    doc.addImage(qrcodeImgData, 'PNG', 10, 40, 50, 50);
                    console.log('QRCode image added to PDF');
                }

                doc.save('event-details.pdf');
                qrElement.innerHTML = '';
                console.log('PDF saved and QR element cleared');
            }
        });
    } else {
        console.log('Button not found in the document');
    }
}


module.exports = { setupDownloadPdfButton };
