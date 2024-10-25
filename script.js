class Registrasi {
    constructor(userType, email, contact, password) {
        this.userType = userType;
        this.email = email;
        this.contact = contact;
        this.password = password;
        this.formFields = "";
    }

    validateInput() {
        if (this.email && this.password && this.contact) {
            return true;
        }
        return false;
    }

    register() {
        if (this.validateInput()) {
            console.log("Registration successful!");
        } else {
            console.log("Invalid input. Please fill in all required fields.");
        }
    }

    updateFormFields(newFields) {
        this.formFields = newFields;
    }

    displayRegistrationResult() {
        console.log("Displaying registration result...");
    }
}

class UMKMRegistration extends Registrasi {
    constructor(userType, email, contact, password, businessName, businessType, address) {
        super(userType, email, contact, password);
        this.businessName = businessName;
        this.businessType = businessType;
        this.address = address;
    }

    register() {
        if (this.validateInput() && this.businessName && this.businessType && this.address) {
            console.log("UMKM registration successful!");
        } else {
            console.log("Invalid UMKM input.");
        }
    }
}

class JobSeekerRegistration extends Registrasi {
    constructor(userType, email, contact, password, fullName, jobField, skills) {
        super(userType, email, contact, password);
        this.fullName = fullName;
        this.jobField = jobField;
        this.skills = skills;
    }

    register() {
        if (this.validateInput() && this.fullName && this.jobField && this.skills) {
            console.log("Job seeker registration successful!");
        } else {
            console.log("Invalid job seeker input.");
        }
    }
}

class FormulirLogin {
    constructor(email, kataSandi) {
        this.email = email;
        this.kataSandi = kataSandi;
    }

    kirimLogin() {
        console.log(`Login attempt: Email - ${this.email}, Password - ${this.kataSandi}`);
        return { email: this.email, kataSandi: this.kataSandi };
    }
}

class AkunPengguna {
    constructor(email, kataSandi) {
        this.email = email;
        this.kataSandi = kataSandi;
    }

    cekValidasi() {
        if (this.email && this.kataSandi) {
            console.log("Validasi akun berhasil.");
            return true;
        } else {
            console.log("Validasi akun gagal.");
            return false;
        }
    }
}

class KontrolLogin {
    constructor() {
        this.statusLogin = false;
    }

    verifikasiLogin(akun, formulir) {
        if (akun.cekValidasi() && akun.email === formulir.email && akun.kataSandi === formulir.kataSandi) {
            this.statusLogin = true;
            console.log("Login berhasil.");
        } else {
            this.statusLogin = false;
            console.log("Login gagal.");
        }
    }

    kirimNotifikasi() {
        if (this.statusLogin) {
            console.log("Notifikasi: Login berhasil.");
        } else {
            console.log("Notifikasi: Login gagal.");
        }
    }
}

class VerifikasiController {
    constructor() {
        this.tipePengguna = "";
    }

    deteksiTipePengguna(email) {
        if (email.includes("umkm")) {
            this.tipePengguna = "UMKM";
        } else {
            this.tipePengguna = "Job Seeker";
        }
        console.log(`Tipe Pengguna: ${this.tipePengguna}`);
    }
}

let kontrolLogin = new KontrolLogin();
kontrolLogin.verifikasiLogin(akunPengguna, formulirLogin);
kontrolLogin.kirimNotifikasi();

let verifikasiController = new VerifikasiController();
verifikasiController.deteksiTipePengguna(formulirLogin.email);


