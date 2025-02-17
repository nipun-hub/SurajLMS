function errorHadel(value, type = null) {
    if (type == null) {
        if (value.trim() == "success") { return 'Successfull'; }
        else if (value == " upload Error") { return 'Upload Problem'; }
        else if (value == " Insert Error") { return 'Insert Problem'; }
        else if (value == " alredy register") { return 'Alredy Registered'; }
        else if (value == " load Error") { return 'Load Problem'; }
        else if (value == " error") { return 'Something Wrong'; }
        else if (value == " invalid") { return 'Invalid Input'; }
        else if (value == " undefind") { return 'undefind '; }
        else if (value == " alredy add payment") { return 'alredy add payment'; }
        else if (value == " Connection Error") { return 'Main Problem Please Inform Admin (error_001)'; }
        else {
            return "Main Problem Please Inform Admin";
        }
    }
}