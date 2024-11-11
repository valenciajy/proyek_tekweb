// Example content, replace this with your actual content
const content = 'The Project Gutenberg EBook of Through the Looking-Glass, by Charles  Dodgson, AKA Lewis Carroll This eBook is for the use of anyone anywhere at no cost and with almost no restrictions whatsoever. You may copy it, give it away or re-use it under the terms of the Project Gutenberg License included with this eBook or online at www.gutenberg.org Title: Through the Looking-Glass Author:  Charles Dodgson, AKA Lewis Carroll Release Date: February, 1991 [EBook #12] Last Updated: October 6, 2016 Language: English *** START OF  THIS PROJECT GUTENBERG EBOOK THROUGH THE LOOKING-GLASS *** Produced by David Widger THROUGH THE LOOKING-GLASS By Lewis Carroll The Millennium Fulcrum Edition 1.7 * * *'
// Number of characters per page
const charsPerPage = 3000;

// Current page index
let currentPageIndex = 0;

// Function to split content into pages
function splitContentIntoPages(content, charsPerPage) {
    const pages = [];
    let currentPage = '';

    content.split(' ').forEach(word => {
        if (currentPage.length + word.length <= charsPerPage) {
            currentPage += word + ' ';
        } else {
            pages.push(currentPage.trim());
            currentPage = word + ' ';
        }
    });

    // Add the last page
    if (currentPage.trim() !== '') {
        pages.push(currentPage.trim());
    }

    return pages;
}

// Function to display content on the current page
function displayCurrentPage() {
    const contentDiv = document.getElementById('content');
    const pages = splitContentIntoPages(content, charsPerPage);

    // Check if the current page index is valid
    if (currentPageIndex >= 0 && currentPageIndex < pages.length) {
        contentDiv.innerHTML = `<p>${pages[currentPageIndex]}</p>`;
    } else {
        contentDiv.innerHTML = '<p>Page not found</p>';
    }
}

// Function to generate pagination controls
function generatePaginationControls() {
    const paginationDiv = document.getElementById('pagination');
    paginationDiv.innerHTML = '';

    const prevButton = document.createElement('button');
    prevButton.textContent = 'Prev';
    prevButton.addEventListener('click', function () {
        if (currentPageIndex > 0) {
            currentPageIndex--;
            displayCurrentPage();
            updatePageNumber();
        }
    });

    const pageNumberSpan = document.createElement('span');
    pageNumberSpan.id = 'pageNumber';
    pageNumberSpan.textContent = currentPageIndex + 1; // Initial page number

    const nextButton = document.createElement('button');
    nextButton.textContent = 'Next';
    nextButton.addEventListener('click', function () {
        const pages = splitContentIntoPages(content, charsPerPage);
        if (currentPageIndex < pages.length - 1) {
            currentPageIndex++;
            displayCurrentPage();
            updatePageNumber();
        }
    });
    prevButton.style.marginRight = '50px'; // Adjust the value as needed
    nextButton.style.marginLeft = '50px'; // Adjust the value as needed

    paginationDiv.appendChild(prevButton);
    paginationDiv.appendChild(pageNumberSpan);
    paginationDiv.appendChild(nextButton);

    updatePageNumber();
}

// Function to update the displayed page number
function updatePageNumber() {
    const pageNumberSpan = document.getElementById('pageNumber');
    if (pageNumberSpan) {
        pageNumberSpan.textContent = currentPageIndex + 1;
    }
}

// Initial page display
displayCurrentPage();
generatePaginationControls();

