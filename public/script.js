async function checkLoginStatus() {
    try {
        const response = await fetch('../constant/functions.php?user_info=true');
        const data = await response.json(); // This line must receive valid JSON

        if (data && data.username) {
            console.log('User is logged in');
            document.getElementById('basket').classList.remove('hidden');
            document.getElementById('profile').classList.remove('hidden');
            document.getElementById('signin').classList.add('hidden');
        } else {
            console.log('User is NOT logged in');
            document.getElementById('basket').classList.add('hidden');
            document.getElementById('signin').classList.remove('hidden');
            document.getElementById('profile').classList.add('hidden');
        }
    } catch (error) {
        console.error('Error checking login status:', error);
    }
}

// Function to show user information
function showUserInfo(userInfo) {
    if (userInfo) {
        document.getElementById('username').textContent = userInfo.username; // Update username
        document.getElementById('email').textContent = userInfo.email; // Update email
    }
}

// Logout function
async function logout() {
    try {
        const formData = new FormData();
        formData.append('logout', 'true'); // Trigger logout

        await fetch('../constant/functions.php', {
            method: 'POST',
            body: formData // Send form data instead of JSON
        });
        location.reload(); // Reload the page after logout
    } catch (error) {
        console.error('Error logging out:', error);
    }
}

// Check login status on page load
document.addEventListener('DOMContentLoaded', checkLoginStatus);

document.addEventListener('DOMContentLoaded', function() {
    fetch('path_to_php_script.php') // Replace with the path to your PHP file
        .then(response => response.json())
        .then(data => {
            const marketplace = document.getElementById('marketplaceItems');
            data.forEach(item => {
                const itemElement = `
                    <div class="w-96 p-5">
                        <div class="border rounded-lg p-5">
                            <img class="h-50 w-full object-cover" src="${item.image}" alt="${item.name}">
                            <h2 class="mt-2 text-2xl font-bold text-gray-700">${item.name}</h2>
                            <p>${item.description}</p>
                            <p class="font-semibold text-green-700">$${item.price}</p>
                        </div>
                    </div>`;
                marketplace.innerHTML += itemElement;
            });
        })
        .catch(error => console.log('Error fetching items:', error));
});

// Function to fetch vegetable data
async function fetchVeggies() {
    try {
        const response = await fetch('YOUR_API_URL_HERE'); // Replace with your API URL
        const veggies = await response.json();

        // Populate the slider with items
        const slider = document.getElementById('slider');
        slider.innerHTML = ''; // Clear existing items if any

        veggies.forEach(veggie => {
            const li = document.createElement('li');
            li.className = 'w-96 p-5';
            li.innerHTML = `
                <div class="border rounded-lg p-5">
                    <img class="h-50 w-full object-cover" src="${veggie.image}" alt="${veggie.name}">
                    <h2 class="mt-2 text-2xl font-bold text-gray-700">${veggie.name}</h2>
                </div>
            `;
            slider.appendChild(li);
        });
    } catch (error) {
        console.error('Error fetching vegetable data:', error);
    }
}

// Call the function to fetch and display veggies
fetchVeggies();
