// =========  Shop Filter
const filerCurrentElement = document.querySelector('.Recommended');
const shotFilerITem = document.querySelectorAll('.sortFilter li');
const dropdown = document.querySelector('.select-dropdown');
filerCurrentElement.addEventListener('click', (e) => {
  e.preventDefault();

  if (!dropdown.classList.contains('toggle')) {
    dropdown.classList.add('toggle');
  } else {
    dropdown.classList.remove('toggle');
  }
});

shotFilerITem.forEach((button) => {
  button.addEventListener('click', (e) => {
    e.preventDefault();
    const slectedElement = e.target.innerHTML;
    filerCurrentElement.querySelector('span').innerHTML = slectedElement;
    dropdown.classList.remove('toggle');
  });
});

// ===== Shop Filter Toggle

const shopFilterButton = document.querySelectorAll('.shopFilterButton');
const dropdownSMmain = document.querySelector('.dropdown-sm-main');
const dropSortMain = document.querySelector('.dropdown-sort-main');

dropdownSMmain.addEventListener('click', (e) => {
  e.preventDefault();
  dropdownSMmain.classList.toggle('toggle-active');
  dropSortMain.classList.remove('toggle-active');
});

dropSortMain.addEventListener('click', (e) => {
  e.preventDefault();
  dropSortMain.classList.toggle('toggle-active');
  dropdownSMmain.classList.remove('toggle-active');
});

shopFilterButton.forEach((item) => {
  item.addEventListener('click', (e) => {
    e.preventDefault();
    const showDropdown = document.querySelector('.active-btn');

    shopFilterFun(item);
    // Remove the show-dropdown class from other items
    if (showDropdown && showDropdown !== item) {
      shopFilterFun(showDropdown);
    }
  });
});

const shopFilterFun = (item) => {
  // 3.1. Select each dropdown content

  if (item.classList.contains('active-btn')) {
    item.classList.remove('active-btn');
    dropdownSMmain.classList.remove('toggle-active');
    dropSortMain.classList.remove('toggle-active');
  } else {
    item.classList.add('active-btn');
  }
};

// ======= more Filter

const filterdropdown = document.querySelectorAll('.filterdropdown');

filterdropdown.forEach((button) => {
  button.querySelector('a').addEventListener('click', (e) => {
    e.preventDefault();
    const showDropdown = document.querySelector('.active-btn');

    moreFilterFun(button);
    // Remove the show-dropdown class from other items
    if (showDropdown && showDropdown !== button) {
      moreFilterFun(showDropdown);
    }
  });
});

const moreFilterFun = (item) => {
  // 3.1. Select each dropdown content

  if (item.classList.contains('active-btn')) {
    item.classList.remove('active-btn');
  } else {
    item.classList.add('active-btn');
  }
};

// ====== Shop Search Button

const shopSearchButton = document.querySelector('.search-btn');
shopSearchButton.addEventListener('click', (e) => {
  e.preventDefault('');
  document.querySelector('.shop_search').classList.toggle('active-search-shop');
});

//=== Active filter
const doneButton = document.querySelectorAll('.done-button');

const sizeFilteractive = document.querySelectorAll(
  '.dropdown-filter .size li a'
);
const catagoryList = document.querySelectorAll(
  '.dropdown-filter .catagory-list li a'
);

sizeFilteractive.forEach((button) => {
  button.addEventListener('click', (e) => {
    e.preventDefault();
    const showDropdown = document.querySelector('.active');

    toggleItem(button);
    // Remove the show-dropdown class from other items
    if (showDropdown && showDropdown !== button) {
      toggleItem(showDropdown);
    }
  });
});

catagoryList.forEach((button) => {
  button.addEventListener('click', (e) => {
    e.preventDefault();
    const showDropdown = document.querySelector('.active');

    toggleItem(button);
    // Remove the show-dropdown class from other items
    if (showDropdown && showDropdown !== button) {
      toggleItem(showDropdown);
    }
  });
});

const sizeFilter = (item) => {
  // 3.1. Select each dropdown content

  if (item.classList.contains('active')) {
    item.classList.remove('active');
  } else {
    item.classList.add('active');
  }
};

doneButton.forEach((button) => {
  button.addEventListener('click', (e) => {
    e.preventDefault();
    const showDropdown = document.querySelector('.active-btn');

    showDropdown.classList.remove('active-btn');
  });
});
