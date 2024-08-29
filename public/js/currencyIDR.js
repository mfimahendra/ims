function convertToIDR(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(number);
}

function convertToINT(string){        
    return parseInt(string.replace(/[^0-9]/g, ''));
}


function numberToWords(number) {
    var words = [
        "", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"
    ];

    function convertToWords(n) {
        if (n < 12) {
            return words[n];
        } else if (n < 20) {
            return words[n - 10] + " belas ";
        } else if (n < 100) {
            return words[Math.floor(n / 10)] + " puluh " + words[n % 10];
        } else if (n < 200) {
            return "seratus " + convertToWords(n - 100);
        } else if (n < 1000) {
            return words[Math.floor(n / 100)] + " ratus " + convertToWords(n % 100);
        } else if (n < 2000) {
            return "seribu " + convertToWords(n - 1000);
        } else if (n < 1000000) {
            return convertToWords(Math.floor(n / 1000)) + " ribu " + convertToWords(n % 1000);
        } else if (n < 1000000000) {
            return convertToWords(Math.floor(n / 1000000)) + " juta " + convertToWords(n % 1000000);
        } else if (n < 1000000000000) {
            return convertToWords(Math.floor(n / 1000000000)) + " milyar " + convertToWords(n % 1000000000);
        } else if (n < 1000000000000000) {
            return convertToWords(Math.floor(n / 1000000000000)) + " triliun " + convertToWords(n % 1000000000000);
        } else {
            return "Number too large";
        }
    }

    return convertToWords(number);
}