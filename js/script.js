// Apply the saved theme to every page
(function () {
    const savedTheme = localStorage.getItem("sportshub_theme") || "dark";
    document.documentElement.setAttribute("data-theme", savedTheme);
})();
// ========================================
// SportsHub - Common JavaScript
// ========================================


// ----------------------------------------
// DELETE CONFIRMATION
// ----------------------------------------

function confirmDelete(message = "Are you sure you want to delete this record?") {
    return confirm(message);
}


// ----------------------------------------
// PASSWORD SHOW / HIDE
// ----------------------------------------


function togglePassword(inputId) {

    const passwordInput = document.getElementById(inputId);

    if (!passwordInput) return;

    const toggleButton = passwordInput
        .closest(".password-wrapper")
        ?.querySelector(".password-toggle");

    const isPasswordHidden = passwordInput.type === "password";

    passwordInput.type = isPasswordHidden ? "text" : "password";

    if (toggleButton) {
        toggleButton.setAttribute(
            "aria-label",
            isPasswordHidden ? "Hide password" : "Show password"
        );

        toggleButton.setAttribute(
            "aria-pressed",
            String(isPasswordHidden)
        );
    }
}


// ----------------------------------------
// FORM VALIDATION
// ----------------------------------------

function validateForm(form) {

    const inputs = form.querySelectorAll("input[required], select[required], textarea[required]");

    for (let input of inputs) {

        if (input.value.trim() === "") {

            alert("Please fill in all required fields.");

            input.focus();

            return false;
        }
    }

    return true;
}


// ----------------------------------------
// SEARCH TABLE
// ----------------------------------------

function searchTable(inputId, tableId) {

    const searchInput = document.getElementById(inputId);
    const table = document.getElementById(tableId);

    if (!searchInput || !table) {
        return;
    }

    searchInput.addEventListener("keyup", function () {

        const searchValue = this.value.toLowerCase();

        const rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {

            const rowText = rows[i].textContent.toLowerCase();

            if (rowText.includes(searchValue)) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }

    });
}


// ----------------------------------------
// DATE VALIDATION
// ----------------------------------------

function validateDateNotPast(dateInputId) {

    const dateInput = document.getElementById(dateInputId);

    if (!dateInput) {
        return;
    }

    const today = new Date();

    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, "0");
    const day = String(today.getDate()).padStart(2, "0");

    const todayString = `${year}-${month}-${day}`;

    dateInput.min = todayString;
}


// ----------------------------------------
// PAGE LOAD MESSAGE
// ----------------------------------------

document.addEventListener("DOMContentLoaded", function () {

    console.log("SportsHub JavaScript loaded successfully.");

});

// ========================================
// FACILITY BOOKING VALIDATION
// ========================================

function validateBookingForm() {

    const facility = document.getElementById("facility_id");
    const date = document.getElementById("booking_date");
    const startTime = document.getElementById("start_time");
    const endTime = document.getElementById("end_time");


    // Check required fields
    if (
        !facility.value ||
        !date.value ||
        !startTime.value ||
        !endTime.value
    ) {

        alert("Please fill in all booking fields.");

        return false;
    }


    // Check date
    const selectedDate = new Date(date.value);
    const today = new Date();

    today.setHours(0, 0, 0, 0);


    if (selectedDate < today) {

        alert("You cannot book a facility for a past date.");

        date.focus();

        return false;
    }


    // Check time
    if (startTime.value >= endTime.value) {

        alert("End time must be later than start time.");

        endTime.focus();

        return false;
    }


    return true;
}

document.addEventListener('DOMContentLoaded', function() {
  // Booking search
  const searchInput = document.getElementById('bookingSearch');
  const table = document.getElementById('bookingTable');
  if (searchInput && table) {
    searchInput.addEventListener('keyup', function() {
      let filter = this.value.toLowerCase();
      let rows = table.getElementsByTagName('tr');
      for (let i = 1; i < rows.length; i++) {
        let rowText = rows[i].textContent.toLowerCase();
        rows[i].style.display = rowText.includes(filter)? "" : "none";
      }
    });
  }

  // Facility search
  const facSearch = document.getElementById('facilitySearch');
  const facTable = document.getElementById('facilitiesTable');
  if (facSearch && facTable) {
    facSearch.addEventListener('keyup', function() {
      let filter = this.value.toLowerCase();
      let rows = facTable.getElementsByTagName('tr');
      for (let i = 1; i < rows.length; i++) {
        let rowText = rows[i].textContent.toLowerCase();
        rows[i].style.display = rowText.includes(filter)? "" : "none";
      }
    });
  }
});


// ========================================
// PASSWORD SHOW / HIDE
// ========================================

function togglePassword(inputId) {

    const passwordInput = document.getElementById(inputId);

    if (!passwordInput) {
        return;
    }

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}


// ========================================
// LOGIN VALIDATION
// ========================================

function validateLoginForm() {

    const email = document.getElementById("email");
    const password = document.getElementById("password");

    if (!email.value.trim()) {

        alert("Please enter your email.");

        email.focus();

        return false;
    }


    if (!email.value.includes("@")) {

        alert("Please enter a valid email address.");

        email.focus();

        return false;
    }


    if (!password.value.trim()) {

        alert("Please enter your password.");

        password.focus();

        return false;
    }


    return true;
}


// ========================================
// REGISTRATION VALIDATION
// ========================================

function validateRegisterForm() {

    const name = document.getElementById("full_name");
    const email = document.getElementById("email");
    const password = document.getElementById("password");


    if (!name.value.trim()) {

        alert("Please enter your full name.");

        name.focus();

        return false;
    }


    if (!email.value.trim()) {

        alert("Please enter your email.");

        email.focus();

        return false;
    }


    if (!email.value.includes("@")) {

        alert("Please enter a valid email address.");

        email.focus();

        return false;
    }


    if (password.value.length < 6) {

        alert("Password must contain at least 6 characters.");

        password.focus();

        return false;
    }


    return true;
}


// ========================================
// BOOKING STATUS CHART
// ========================================

function createBookingChart() {

    const canvas = document.getElementById("bookingChart");

    if (!canvas) {
        return;
    }

    const ctx = canvas.getContext("2d");

    const pending = bookingData.pending;
    const approved = bookingData.approved;
    const rejected = bookingData.rejected;

    const values = [
        pending,
        approved,
        rejected
    ];

    const labels = [
        "Pending",
        "Approved",
        "Rejected"
    ];

    const maxValue = Math.max(...values, 1);

    const barWidth = 100;
    const gap = 70;

    const startX = 70;
    const bottomY = 250;

    const chartHeight = 180;


    // Clear canvas

    ctx.clearRect(
        0,
        0,
        canvas.width,
        canvas.height
    );


    // Title

    ctx.font = "18px Arial";

    ctx.fillText(
        "Facility Booking Status",
        180,
        30
    );


    // Draw bars

    values.forEach(function(value, index) {

        const barHeight =
            (value / maxValue) * chartHeight;

        const x =
            startX + index * (barWidth + gap);

        const y =
            bottomY - barHeight;


        // Bar

        ctx.fillRect(
            x,
            y,
            barWidth,
            barHeight
        );


        // Value

        ctx.font = "16px Arial";

        ctx.fillText(
            value,
            x + 45,
            y - 10
        );


        // Label

        ctx.font = "14px Arial";

        ctx.fillText(
            labels[index],
            x + 20,
            bottomY + 25
        );

    });


    // X-axis

    ctx.beginPath();

    ctx.moveTo(50, bottomY);

    ctx.lineTo(550, bottomY);

    ctx.stroke();

}


// ========================================
// RUN CHART AFTER PAGE LOAD
// ========================================

document.addEventListener(
    "DOMContentLoaded",
    function() {

        createBookingChart();

    }
);