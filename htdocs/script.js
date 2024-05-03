document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;

        if (!name || !email) {
            alert('Please fill in all fields.');
            return;
        }

        // Submit form data (AJAX can be used for server-side processing)
    });

    // Logout functionality
    const logoutLink = document.getElementById("logout");

    logoutLink.addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default link behavior

        // Send a POST request to log out
        fetch("http://localhost/logout", {
            method: "POST", // Safe method for logout
            headers: {
                "Content-Type": "application/json",
            },
        })
        .then((response) => {
            if (response.ok) {
                // Redirect to the logout page after successful logout
                window.location.href = "logout.html"; // Redirect to logout page
            } else {
                console.error("Logout failed"); // Handle unsuccessful logout
            }
        })
        .catch((error) => {
            console.error("Error during logout:", error); // Handle fetch errors
        });
    });

    // Implement additional functionalities (e.g., budget goal, interactive charts, etc.)
    const budgetGoalInput = document.getElementById('budget-goal');
    const currentSpendingInput = document.getElementById('current-spending');
    const progressElement = document.getElementById('progress');
    
    function updateProgress() {
        const budgetGoal = parseFloat(budgetGoalInput.value);
        const currentSpending = parseFloat(currentSpendingInput.value);

        if (isNaN(budgetGoal) || isNaN(currentSpending) || budgetGoal <= 0) {
            progressElement.textContent = 'N/A';
            return;
        }

        const progress = (currentSpending / budgetGoal) * 100;
        progressElement.textContent = `${progress.toFixed(2)}%`;
    }

    budgetGoalInput.addEventListener('input', updateProgress);
    currentSpendingInput.addEventListener('input', updateProgress);

    // Interactive charts
    const ctxPie = document.getElementById('pie-chart').getContext('2d');
    const ctxBar = document.getElementById('bar-graph').getContext('2d');

    const categories = ['Groceries', 'Utilities', 'Rent', 'Entertainment'];
    const spending = [100, 50, 200, 80]; // Example spending amounts for each category

    const myPieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: categories,
            datasets: [{
                data: spending,
                backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0']
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Spending by Category'
            }
        }
    });

    const myBarChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: categories,
            datasets: [{
                label: 'Spending',
                data: spending,
                backgroundColor: '#007bff'
            }]
        },
        options: {
            legend: {
                display: false,
            },
            title: {
                display: true,
                text: 'Spending by Category'
            },
        },
    });

    // Implement additional features based on your requirements
    const expenseForm = document.getElementById('expense-form');
    const expenseList = document.getElementById('expenses');
    const totalElement = document.getElementById('total');

    let totalExpense = 0;

    function addExpense(name, amount) {
        const li = document.createElement("li");
        li.innerHTML = `<span>${name}</span> <span>$${amount}</span>`;
        expenseList.appendChild(li);

        totalExpense += parseFloat(amount);
        totalElement.textContent = `Total: $${totalExpense.toFixed(2)}`;
    }

    expenseForm.addEventListener("submit", function(event) {
        event.preventDefault();

        const name = document.getElementById("name").value;
        const amount = document.getElementById("amount").value;

        if (name && amount) {
            addExpense(name, amount);
            expenseForm.reset(); // Clear form fields
        } else {
            alert("Please fill in both name and amount.");
        }
    });

   function closePopup() {
    document.getElementById('popup').style.display = 'none';
}

// To show the popup, you can call this function when needed
function showPopup() {
    document.getElementById('popup').style.display = 'flex';
}
const categorieschart = JSON.parse(document.getElementById('chart-data').getAttribute('data-categories'));
const spendingchart = JSON.parse(document.getElementById('chart-data').getAttribute('data-spending'));

const ctx = document.getElementById('expense-chart').getContext('2d');
const expensePieChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: categories,
        datasets: [{
            data: spending,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
            hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
        }]
    },
    options: {
        responsive: true,
        legend: {
            position: 'top',
        },
        animation: {
            animateScale: true,
            animateRotate: true
        }
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('expense_amount');
    const dollarSign = document.createElement('span');
    dollarSign.className = 'dollar-sign';
    dollarSign.textContent = '$';
    
    const inputWrapper = document.createElement('div');
    inputWrapper.className = 'input-group';
    inputWrapper.appendChild(dollarSign);
    inputWrapper.appendChild(amountInput.cloneNode(true));
    amountInput.parentNode.replaceChild(inputWrapper, amountInput);

    const decimalRegex = /^\d*\.?\d*$/;

    amountInput.addEventListener('input', function() {
        const sanitizedValue = this.value.replace(/[^0-9.]/g, '');
        this.value = sanitizedValue ? '$' + sanitizedValue.replace(decimalRegex, '') : '';
    });
});
});
