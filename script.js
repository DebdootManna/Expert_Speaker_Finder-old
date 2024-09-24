// Constants
const API_URL = ''; // Empty string to use relative URLs

// DOM Elements
const speakerForm = document.getElementById('speaker-form');
const submissionConfirmation = document.getElementById('submission-confirmation');
const adminLoginForm = document.getElementById('admin-login-form');
const adminDashboard = document.getElementById('admin-dashboard');
const speakerSubmissions = document.getElementById('speaker-submissions');

// Event Listeners
if (speakerForm) {
    speakerForm.addEventListener('submit', handleSpeakerSubmission);
}

if (adminLoginForm) {
    adminLoginForm.addEventListener('submit', handleAdminLogin);
}

// Functions
async function handleSpeakerSubmission(e) {
    e.preventDefault();
    const name = document.getElementById('speaker-name').value;
    const expertise = document.getElementById('speaker-expertise').value;
    const background = document.getElementById('speaker-background').value;

    try {
        const response = await fetch(`${API_URL}/api/speakers`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name, expertise, background }),
        });

        if (response.ok) {
            speakerForm.reset();
            showSubmissionConfirmation();
        } else {
            throw new Error('Failed to submit speaker');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to submit speaker. Please try again.');
    }
}

function showSubmissionConfirmation() {
    speakerForm.classList.add('hidden');
    submissionConfirmation.classList.remove('hidden');
    setTimeout(() => {
        submissionConfirmation.classList.add('hidden');
        speakerForm.classList.remove('hidden');
    }, 5000);
}

async function handleAdminLogin(e) {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    try {
        const response = await fetch(`${API_URL}/api/admin/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ username, password }),
        });

        if (response.ok) {
            const { token } = await response.json();
            localStorage.setItem('adminToken', token);
            document.getElementById('admin-login').classList.add('hidden');
            adminDashboard.classList.remove('hidden');
            loadAdminDashboard();
        } else {
            throw new Error('Invalid credentials');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Invalid credentials. Please try again.');
    }
}

async function loadAdminDashboard() {
    try {
        const response = await fetch(`${API_URL}/api/speakers`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('adminToken')}`,
            },
        });

        if (response.ok) {
            const submissions = await response.json();
            displaySubmissions(submissions);
        } else {
            throw new Error('Failed to load submissions');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load submissions. Please try again.');
    }
}

function displaySubmissions(submissions) {
    speakerSubmissions.innerHTML = '';
    submissions.forEach(submission => {
        const submissionElement = createSubmissionElement(submission);
        speakerSubmissions.appendChild(submissionElement);
    });
}

function createSubmissionElement(submission) {
    const submissionElement = document.createElement('div');
    submissionElement.className = 'submission';
    submissionElement.innerHTML = `
        <h3>${submission.name}</h3>
        <p><strong>Expertise:</strong> ${submission.expertise}</p>
        <p><strong>Background:</strong> ${submission.background}</p>
        <p><strong>Status:</strong> ${submission.status}</p>
        <div class="submission-actions">
            <button class="accept-btn" data-id="${submission.id}">Accept</button>
            <button class="reject-btn" data-id="${submission.id}">Reject</button>
        </div>
    `;

    submissionElement.querySelector('.accept-btn').addEventListener('click', () => handleAdminAction('accept', submission.id));
    submissionElement.querySelector('.reject-btn').addEventListener('click', () => handleAdminAction('reject', submission.id));

    return submissionElement;
}

async function handleAdminAction(action, submissionId) {
    try {
        const response = await fetch(`${API_URL}/api/speakers/${submissionId}/${action}`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('adminToken')}`,
            },
        });

        if (response.ok) {
            loadAdminDashboard();
        } else {
            throw new Error(`Failed to ${action} submission`);
        }
    } catch (error) {
        console.error('Error:', error);
        alert(`Failed to ${action} submission. Please try again.`);
    }
}

// Check if admin is already logged in
if (adminDashboard && localStorage.getItem('adminToken')) {
    document.getElementById('admin-login').classList.add('hidden');
    adminDashboard.classList.remove('hidden');
    loadAdminDashboard();
}