const header = document.querySelector(".header");
const nav = document.querySelector(".nav");
const navList = document.querySelector(".mobile_list");
const logo = document.querySelector(".logo");
const navIcon = document.querySelector(".nav_icon");
const overlay = document.querySelector(".overlay");
const section1 = document.querySelector(".hero_section");
const questions = document.querySelector(".questions_container");
const showElements = document.querySelectorAll(".show_el");
const mediaQuery1 = window.matchMedia("(max-width: 62rem)");
const membersContainer = document.querySelector(".members_container");
const memberBoxes = document.querySelectorAll(".member_box");
const ctaFormBox = document.querySelector(".cta_content");
const ctaForm = ctaFormBox.querySelector(".cta_form");
const joinFname = ctaFormBox.querySelector("[name='first_name']");
const joinLname = ctaFormBox.querySelector("[name='last_name']");
const joinYear = ctaFormBox.querySelector("[name='educationnal_Level']");
const joinEmail = ctaFormBox.querySelector("[name='email']");
const joinPhone = ctaFormBox.querySelector("[name='phone']");
const joinSubmit = ctaFormBox.querySelector("[name='submit']");
const thanksPopup = document.querySelector(".thanks_popup");
const popupBtn = document.querySelector(".popup_btn");
const contactFormBox = document.querySelector(".contact_section");
const contactForm = contactFormBox.querySelector(".cta_form");
const contactFname = contactFormBox.querySelector("[name='first_name']");
const contactLname = contactFormBox.querySelector("[name='last_name']");
const contactEmail = contactFormBox.querySelector("[name='email']");
const contactSubject = contactFormBox.querySelector("[name='subject']");
const contactMessage = contactFormBox.querySelector("[name='message']");
const contactSubmit = contactFormBox.querySelector("[name='submit']");
const mainContent = document.querySelector("main");
const loading = document.querySelector(".loader_content");

// Wait promise
const wait = function (seconds) {
  return new Promise(function (resolve) {
    setTimeout(resolve, seconds * 1000);
  });
};

// nav icon's click event
const showNavList = function () {
  overlay.classList.toggle("hidden");
  wait(0.05).then(() => overlay.classList.toggle("overlay--active"));
  navIcon.classList.toggle("nav_icon--active");
  navList.classList.toggle("nav_list--active");
  header.classList.toggle("header--active");
};

navIcon.addEventListener("click", showNavList);
overlay.addEventListener("click", showNavList);

navList.addEventListener("click", function (e) {
  const clicked = e.target.closest(".nav_link");
  if (clicked && navList.classList.contains("nav_list--active")) {
    overlay.classList.add("hidden");
    overlay.classList.remove("overlay--active");
    navIcon.classList.remove("nav_icon--active");
    navList.classList.remove("nav_list--active");
    header.classList.remove("header--active");
  }
});

const navHeight = nav.getBoundingClientRect().height;

const stickyNav = function (entries) {
  const [entry] = entries;

  if (!entry.isIntersecting) header.classList.add("sticky");
  else header.classList.remove("sticky");
};

// About Section
const aboutTitlesContainer = document.querySelector(".about_titles");
const aboutTitles = document.querySelectorAll(".about_title");
const aboutparags = document.querySelectorAll(".about_parag");
aboutTitlesContainer.addEventListener("click", function (e) {
  const clickedTitle = e.target.closest(".about_title");
  if (!clickedTitle) return;
  aboutTitles.forEach((title) => {
    title.classList.remove("about_title--active");
  });
  clickedTitle.classList.add("about_title--active");
  aboutparags.forEach((parag) => parag.classList.add("hidden"));
  document
    .querySelector(
      `[data-parag = '${clickedTitle.getAttribute("data-title")}']`
    )
    .classList.remove("hidden");
});

// Animation
window.addEventListener("scroll", function () {
  showElements.forEach((element) => {
    const elementPosition = element.getBoundingClientRect().top;
    const windowHeight = window.innerHeight;

    if (elementPosition < windowHeight)
      if (element.classList.contains("member_leader") && !mediaQuery1.matches)
        element.classList.add("animate__fadeInTop--leader");
      else element.classList.add("animate__fadeInTop");
  });
});

// Leadership section
membersContainer.addEventListener("mouseover", function (e) {
  const clickedBox = e.target.closest(".member_box");
  if (!clickedBox) return;
  memberBoxes.forEach((box) => box.classList.remove("animated_box"));
  clickedBox.classList.add("animated_box");
});

if (mediaQuery1.matches) {
  document.querySelector(".member_leader").classList.add("hidden");
  membersContainer.insertAdjacentHTML(
    "afterbegin",
    `<div class="member_box member_leader flex">
  <div class="member_img">
    <img src="./media/oussama.png" alt="Besecure President" />
  </div>
  <h3 class="member_name">OUSSAMA FANNOUCH</h3>
  <p class="member_profession">Club President</p>
  <ul class="member_social_list flex">
    <li class="member_social flex">
      <a href="mailto:oussamafannouch@gmail.com">
        <i class="fa-regular fa-envelope"></i
      ></a>
    </li>
    <li class="member_social flex">
      <a
        href="https://www.instagram.com/oussamafannouch/"
        target="_blank"
        ><i class="fa-brands fa-instagram"></i
      ></a>
    </li>
    <li class="member_social flex">
      <a href="https://www.linkedin.com/in/oussamafannouch/"
        ><i class="fa-brands fa-linkedin-in"></i
      ></a>
    </li>
  </ul>
</div>`
  );
}
const inputBorderAnimated = function (e) {
  const focused = e.target
    .closest(".input_container")
    .querySelector(".animated_border");
  if (!focused) return;
  focused.classList.toggle("animated_border--active");
};
const inputs = document.querySelectorAll(".input:not(select)");
inputs.forEach((inp) => {
  inp.addEventListener("focus", function (e) {
    inputBorderAnimated(e);
  });
  inp.addEventListener("blur", function (e) {
    inputBorderAnimated(e);
  });
});

inputs.forEach((input) => {
  input.addEventListener("input", () => {
    if (input.value) {
      input
        .closest(".input_container")
        .querySelector("label")
        .classList.add("has_value");
    }
  });
});

// Email Sending

const showPopup = function (joinSubmit, msg, params) {
  const serviceID = "service_5t4tdv7";
  const templateID = "template_dtff8up";
  emailjs.send(serviceID, templateID, params).then((res) => {
    joinSubmit.value = msg;
    thanksPopup.classList.remove("hidden");
    overlay.classList.remove("hidden");
    overlay.classList.add("overlay--active");
  });
};

function joinSendEmail() {
  const msg = `Join Request from ${joinFname.value}
        First Name: ${joinFname.value}
        Last Name: ${joinLname.value}
        Year: ${joinYear.value}
        Email: ${joinEmail.value}
        Phone number: ${joinPhone.value}`;

  var params = {
    name: joinFname.value,
    email: joinEmail.value,
    subject: "Joining B-Secure",
    message: msg,
  };
  showPopup(joinSubmit, "Request To Join", params);
  joinFname.value = joinLname.value = joinEmail.value = joinPhone.value = "";
}
ctaForm.addEventListener("submit", function (e) {
  e.preventDefault();
  joinSubmit.value = "Processing...";
  joinSubmit.disabled = true;
  joinSendEmail();
});
function contactSendEmail() {
  const msg = `Message from ${contactFname.value}
        First Name: ${contactFname.value}
        Last Name: ${contactLname.value}
        Email: ${contactEmail.value}
        Message: ${contactMessage.value}`;

  var params = {
    name: contactFname.value,
    email: contactEmail.value,
    subject: contactSubject.value,
    message: msg,
  };
  showPopup(contactSubmit, "Send Message", params);
  contactFname.value =
    contactLname.value =
    contactEmail.value =
    contactSubject.value =
    contactMessage.value =
      "";
}
contactForm.addEventListener("submit", function (e) {
  e.preventDefault();
  contactSubmit.value = "Processing...";
  contactSubmit.disabled = true;
  contactSendEmail();
});
popupBtn.addEventListener("click", function () {
  thanksPopup.classList.add("hidden");
  overlay.classList.add("hidden");
  overlay.classList.remove("overlay--active");
  joinSubmit.disabled = false;
  contactSubmit.disabled = false;
});
const loadingContent = function () {
  wait(1.2).then(() => {
    loading.classList.add("hidden");
    header.classList.remove("hidden");
    mainContent.classList.remove("hidden");
  });
};
window.addEventListener("load", function () {
  /* wait(0.5).then(() => loadingContent()); */
  loadingContent();
});

// script.js
document.querySelectorAll('a[href^="/frontend/index.html#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();

    const targetId = this.getAttribute('href').split('#')[1];
    const targetElement = document.getElementById(targetId);

    if (targetElement) {
      window.scrollTo({
        top: targetElement.offsetTop,
        behavior: 'smooth'
      });
    }
  });
});