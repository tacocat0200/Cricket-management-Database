// scripts.js

// Function to validate a form
function validateForm() {
    const name = document.getElementById("player-name").value;
    const email = document.getElementById("player-email").value;
    const errorMessage = document.getElementById("error-message");

    errorMessage.innerHTML = ""; // Clear previous messages

    // Check if name is empty
    if (name === "") {
        errorMessage.innerHTML += "<p>Name is required.</p>";
        return false;
    }

    // Check if email is valid
    if (!validateEmail(email)) {
        errorMessage.innerHTML += "<p>Enter a valid email address.</p>";
        return false;
    }

    return true; // Form is valid
}

// Helper function to validate email format
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

// Event listener for form submission
document.getElementById("player-form").addEventListener("submit", function(event) {
    if (!validateForm()) {
        event.preventDefault(); // Prevent form submission if validation fails
    }
});

// Function to load match results via AJAX
function loadMatchResults() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "api/match_results.php", true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            const results = JSON.parse(xhr.responseText);
            displayMatchResults(results);
        }
    };

    xhr.onerror = function() {
        console.error("Request failed");
    };

    xhr.send();
}

// Function to display match results in the DOM
function displayMatchResults(results) {
    const resultsContainer = document.getElementById("match-results");
    resultsContainer.innerHTML = ""; // Clear previous results

    results.forEach(result => {
        const resultItem = document.createElement("div");
        resultItem.classList.add("result-item");
        resultItem.innerHTML = `
            <h3>${result.home_team} vs ${result.away_team}</h3>
            <p>Score: ${result.home_score} - ${result.away_score}</p>
            <p>Date: ${result.date}</p>
        `;
        resultsContainer.appendChild(resultItem);
    });
}

// Load match results when the page loads
document.addEventListener("DOMContentLoaded", loadMatchResults);
