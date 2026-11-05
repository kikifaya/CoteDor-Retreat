function openMenu() {
    document.getElementById("menuOverlay").classList.add("open");
    document.body.style.overflow = "hidden";
}

function closeMenu() {
    document.getElementById("menuOverlay").classList.remove("open");
    document.body.style.overflow = "";
}

function changeMenuBackground(link) {
    let image = link.getAttribute("data-image");
    document.getElementById("menuBg").style.backgroundImage = "url('images/" + image + "')";
    link.classList.add("active-link");
}

function resetMenuBackground(link) {
    document.getElementById("menuBg").style.backgroundImage = "url('images/hero.jpg')";
    link.classList.remove("active-link");
}

function updateNavbar() {
    let nav = document.getElementById("siteNav");

    if (nav.classList.contains("nav-solid")) {
        return;
    }

    if (window.scrollY > 60) {
        nav.classList.add("scrolled");
    } else {
        nav.classList.remove("scrolled");
    }
}

function hideMissingImages() {
    let images = document.querySelectorAll("img");

    for (let i = 0; i < images.length; i++) {
        if (images[i].complete && images[i].naturalWidth === 0) {
            images[i].classList.add("img-missing");
        }
        images[i].addEventListener("error", function () {
            this.classList.add("img-missing");
        });
    }
}

function showErrors(boxId, errors) {
    let box = document.getElementById(boxId);
    let list = "";

    for (let i = 0; i < errors.length; i++) {
        list += "<li>" + errors[i] + "</li>";
    }

    box.innerHTML = '<div class="alert alert-danger"><strong>Please check the form:</strong><ul class="mb-0">' + list + "</ul></div>";
    box.scrollIntoView({ behavior: "smooth", block: "center" });
}

function isValidEmail(email) {
    let pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(email);
}

function getMealPrice(mealPlan) {
    switch (mealPlan) {
        case "Breakfast":
            return 22;
        case "Half board":
            return 78;
        default:
            return 0;
    }
}

function countNights(checkIn, checkOut) {
    if (checkIn === "" || checkOut === "") {
        return 0;
    }

    let start = new Date(checkIn);
    let end = new Date(checkOut);
    let nights = (end - start) / (1000 * 60 * 60 * 24);

    return nights > 0 ? nights : 0;
}

function calculateTotal() {
    let roomSelect = document.getElementById("room_id");
    let selected = roomSelect.options[roomSelect.selectedIndex];
    let price = Number(selected.getAttribute("data-price")) || 0;
    let nights = countNights(document.getElementById("check_in").value, document.getElementById("check_out").value);
    let guests = Number(document.getElementById("guests").value) || 0;
    let mealPlan = document.getElementById("meal_plan").value;
    let mealPrice = getMealPrice(mealPlan);

    let roomCost = nights * price;
    let mealCost = nights * guests * mealPrice;
    let total = roomCost + mealCost;

    document.getElementById("summaryRoom").textContent = roomSelect.value === "" ? "Not selected" : selected.text;
    document.getElementById("summaryNights").textContent = nights;
    document.getElementById("summaryGuests").textContent = guests;
    document.getElementById("summaryRoomCost").textContent = "$" + roomCost.toFixed(2);
    document.getElementById("summaryMeals").textContent = mealPlan + " ($" + mealCost.toFixed(2) + ")";
    document.getElementById("summaryTotal").textContent = "$" + total.toFixed(2);

    updateProgress();
    return total;
}

function showRoomInfo() {
    let roomSelect = document.getElementById("room_id");
    let selected = roomSelect.options[roomSelect.selectedIndex];
    let info = document.getElementById("roomInfo");

    if (roomSelect.value === "") {
        info.textContent = "";
    } else {
        info.textContent = "You selected the " + selected.text + ", for up to " + selected.getAttribute("data-guests") + " guests.";
    }

    calculateTotal();
}

function updateProgress() {
    let fields = ["room_id", "check_in", "check_out", "guests", "meal_plan", "phone"];
    let filled = 0;

    for (let i = 0; i < fields.length; i++) {
        if (document.getElementById(fields[i]).value.trim() !== "") {
            filled++;
        }
    }

    let percent = Math.round((filled / fields.length) * 100);
    let bar = document.getElementById("bookingProgress");
    bar.style.width = percent + "%";
    bar.parentElement.setAttribute("aria-valuenow", percent);
    document.getElementById("progressText").textContent = percent + "%";
}

function validateBooking(event) {
    let errors = [];
    let roomSelect = document.getElementById("room_id");
    let checkIn = document.getElementById("check_in").value;
    let checkOut = document.getElementById("check_out").value;
    let guests = Number(document.getElementById("guests").value);
    let phone = document.getElementById("phone").value.trim();
    let today = new Date().toISOString().split("T")[0];

    if (roomSelect.value === "") {
        errors.push("Please choose a room.");
    }

    if (checkIn === "") {
        errors.push("Please choose a check-in date.");
    } else if (checkIn < today) {
        errors.push("The check-in date can't be in the past.");
    }

    if (checkOut === "") {
        errors.push("Please choose a check-out date.");
    } else if (checkOut <= checkIn) {
        errors.push("The check-out date must be after the check-in date.");
    }

    if (roomSelect.value !== "") {
        let maxGuests = Number(roomSelect.options[roomSelect.selectedIndex].getAttribute("data-guests"));
        if (guests < 1 || guests > maxGuests) {
            errors.push("This room allows 1 to " + maxGuests + " guests.");
        }
    }

    if (!/^[0-9+\s]{7,20}$/.test(phone)) {
        errors.push("Please enter a valid phone number (numbers, spaces and + only).");
    }

    if (errors.length > 0) {
        event.preventDefault();
        showErrors("formErrors", errors);
        return false;
    }

    return true;
}

function validateRegister(event) {
    let errors = [];
    let fullName = document.getElementById("full_name").value.trim();
    let email = document.getElementById("email").value.trim();
    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm_password").value;

    if (fullName === "") {
        errors.push("Please enter your full name.");
    }

    if (!isValidEmail(email)) {
        errors.push("Please enter a valid email address.");
    }

    if (!/^[A-Za-z0-9_]{4,20}$/.test(username)) {
        errors.push("The username must be 4 to 20 letters, numbers or underscores.");
    }

    if (password.length < 6) {
        errors.push("The password must be at least 6 characters long.");
    } else if (password !== confirmPassword) {
        errors.push("The two passwords don't match.");
    }

    if (errors.length > 0) {
        event.preventDefault();
        showErrors("formErrors", errors);
        return false;
    }

    return true;
}

function validateContact(event) {
    let errors = [];
    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let subject = document.getElementById("subject").value;
    let message = document.getElementById("message").value.trim();

    if (name === "") {
        errors.push("Please enter your name.");
    }

    if (!isValidEmail(email)) {
        errors.push("Please enter a valid email address.");
    }

    if (subject === "") {
        errors.push("Please choose a subject.");
    }

    if (message.length < 10) {
        errors.push("Your message must be at least 10 characters long.");
    }

    if (errors.length > 0) {
        event.preventDefault();
        showErrors("formErrors", errors);
        return false;
    }

    return true;
}

function togglePassword(fieldId, button) {
    let field = document.getElementById(fieldId);

    if (field.type === "password") {
        field.type = "text";
        button.textContent = "Hide";
    } else {
        field.type = "password";
        button.textContent = "Show";
    }
}

function showWelcome(name) {
    let hour = new Date().getHours();
    let greeting;

    if (hour < 12) {
        greeting = "Good morning";
    } else if (hour < 18) {
        greeting = "Good afternoon";
    } else {
        greeting = "Good evening";
    }

    document.getElementById("welcomeMessage").textContent = greeting + ", " + name + ". Welcome back to Côte d'Or Retreat.";
}

window.addEventListener("scroll", updateNavbar);

document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        closeMenu();
    }
});

document.addEventListener("DOMContentLoaded", function () {
    updateNavbar();
    hideMissingImages();

    let welcome = document.getElementById("welcomeMessage");
    if (welcome) {
        showWelcome(welcome.getAttribute("data-name"));
    }

    if (document.getElementById("bookingForm")) {
        showRoomInfo();
    }
});
