 

jest.mock('qrcodejs2', () => ({
    QRCode: jest.fn().mockImplementation(() => ({
        makeCode: jest.fn()
    }))
}));

jest.mock('jspdf', () => ({
    jsPDF: jest.fn().mockImplementation(() => ({
        text: jest.fn(),
        addImage: jest.fn(),
        save: jest.fn()
    }))
}));


describe('PDF and QR Code Generation', () => {
    let QRCodeMock, jsPDFMock;

    beforeEach(() => {
        document.body.innerHTML = `
            <button id="download-pdf-1" class="btn btn-primary">Download Event PDF</button>
            <div id="qrcode" style="display: none;"></div>
        `;

        QRCodeMock = require('qrcodejs2').QRCode;
        jsPDFMock = require('jspdf').jsPDF;

        if (!QRCodeMock || !jsPDFMock) {
            throw new Error("Mocks not initialized");
        }

        QRCodeMock.mockClear();
        jsPDFMock.mockClear();

        const { setupDownloadPdfButton } = require('./pdfQRCode');
        setupDownloadPdfButton(1, 'Test Event', '2024-01-01');
    });

    test('QR Code and PDF generation triggers correctly', () => {
        const button = document.getElementById('download-pdf-1');
        button.click(); 

        expect(QRCodeMock).toHaveBeenCalled();
        expect(QRCodeMock.mock.instances[0].makeCode).toHaveBeenCalledWith({
            text: 'Event ID: 1',
            width: 100,
            height: 100,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        expect(jsPDFMock).toHaveBeenCalled();
        expect(jsPDFMock.mock.instances[0].text).toHaveBeenCalledTimes(3);
        expect(jsPDFMock.mock.instances[0].save).toHaveBeenCalledWith('event-details.pdf');
    });
});
