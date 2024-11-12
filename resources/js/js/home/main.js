
const select = document.querySelectorAll('.select');
const options_list = document.querySelector('.options-list');
const options = document.querySelectorAll('.option');

//show & hide options list
select.forEach((button) => {
  button.addEventListener('click', (e) => {
    e.preventDefault();

    const list = button.querySelector('.options-list .option img');

    const seleced = document.querySelector('.active-language');
    toggleLangu(button);

    languageCutton(button);
    // Remove the show-dropdown class from other items
    if (seleced && seleced !== button) {
      toggleItem(seleced);
    }
  });
});

const toggleLangu = (item) => {
  // 3.1. Select each dropdown content

  if (item.classList.contains('active-language')) {
    item.classList.remove('active-language');
  } else {
    item.classList.add('active-language');
  }
};

options.forEach((element) => {
  element.addEventListener('click', (e) => {
    e.preventDefault();
    const img = element.querySelector('img').attributes;
    const text = element.querySelector('span').innerText;
    const parentNode = element.parentNode.parentElement.querySelector('a');

    const prevdata = element.innerHTML;
    element.innerHTML = parentNode.innerHTML;
    parentNode.innerHTML = prevdata;
  });
});

let languageCutton = (button) => { };

// ===== Wishlist Action Toggle

const wishlistButton = document.querySelectorAll('.save-wisthlist');

wishlistButton.forEach((button) => {
  button.addEventListener('click', (e) => {
    e.preventDefault();
    if (!button.classList.contains('active-button')) {
      button.classList.add('active-button');
    } else {
      button.classList.remove('active-button');
    }
  });
});



const dropdownItems = document.querySelectorAll('.categories-list  ul li a');

dropdownItems.forEach((item) => {
  item.addEventListener('click', () => {
    const showDropdown = document.querySelector('.active');

    toggleItem(item);
    // Remove the show-dropdown class from other items
    if (showDropdown && showDropdown !== item) {
      toggleItem(showDropdown);
    }
  });
});

const toggleItem = (item) => {
  // 3.1. Select each dropdown content

  if (item.classList.contains('active')) {
    item.classList.remove('active');
  } else {
    item.classList.add('active');
  }
};


// ======= Price range Shop filter  ======
const rangeInput = document.querySelectorAll('.range-input input'),
  priceInput = document.querySelectorAll('.price-input input'),
  range = document.querySelector('.slider .progress');
let priceGap = 1000;

priceInput.forEach((input) => {
  input.addEventListener('input', (e) => {
    let minPrice = parseInt(priceInput[0].value),
      maxPrice = parseInt(priceInput[1].value);

    if (maxPrice - minPrice >= priceGap && maxPrice <= rangeInput[1].max) {
      if (e.target.className === 'input-min') {
        rangeInput[0].value = minPrice;
        range.style.left = (minPrice / rangeInput[0].max) * 100 + '%';
      } else {
        rangeInput[1].value = maxPrice;
        range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + '%';
      }
    }
  });
});

rangeInput.forEach((input) => {
  input.addEventListener('input', (e) => {
    let minVal = parseInt(rangeInput[0].value),
      maxVal = parseInt(rangeInput[1].value);

    if (maxVal - minVal < priceGap) {
      if (e.target.className === 'range-min') {
        rangeInput[0].value = maxVal - priceGap;
      } else {
        rangeInput[1].value = minVal + priceGap;
      }
    } else {
      priceInput[0].value = minVal;
      priceInput[1].value = maxVal;
      range.style.left = (minVal / rangeInput[0].max) * 100 + '%';
      range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + '%';
    }
  });
});




const selectedSize = document.querySelectorAll('.sizebtn a');
const colorbtn = document.querySelectorAll('.colors-varient .colorbtn a');
const SizeBox = document.querySelectorAll('.filter-offcanvas  .size-box a');

selectedSize.forEach((button) => {
  button.addEventListener('click', (e) => {
    e.preventDefault();
    const showDropdown = document.querySelector('.active');

    size(button);
    // Remove the show-dropdown class from other items
    if (showDropdown && showDropdown !== button) {
      size(showDropdown);
    }
  });
});

colorbtn.forEach((button) => {
  button.addEventListener('click', (e) => {
    e.preventDefault();
    const showDropdown = document.querySelector('.active');

    size(button);
    // Remove the show-dropdown class from other items
    if (showDropdown && showDropdown !== button) {
      size(showDropdown);
    }
  });
});

SizeBox.forEach((button) => {
  button.addEventListener('click', (e) => {
    e.preventDefault();
    const showDropdown = document.querySelector('.active');

    size(button);
    // Remove the show-dropdown class from other items
    if (showDropdown && showDropdown !== button) {
      size(showDropdown);
    }
  });
});

const size = (item) => {
  // 3.1. Select each dropdown content

  if (item.classList.contains('active')) {
    item.classList.remove('active');
  } else {
    item.classList.add('active');
  }
};

// Pop Modal

const popModal = document.querySelector('.popup-modal');
const closeModal = document.querySelector('.modal-close a');
window.addEventListener('load', () => {
  setTimeout(() => {
    popModal.classList.add('active-popup');
  }, 2000);
});

closeModal.addEventListener('click', (e) => {
  e.preventDefault();
  popModal.classList.remove('active-popup');
});



