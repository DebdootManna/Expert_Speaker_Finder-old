// DOM Elements
const speakerForm = document.getElementById('speaker-form');
const adminLoginBtn = document.getElementById('admin-login-btn');
const adminLoginModal = document.getElementById('admin-login-modal');
const closeModalBtn = document.getElementById('close-modal');
const adminLoginForm = document.getElementById('admin-login-form');
const speakerSubmissions = document.getElementById('speaker-submissions');
const adminDashboard = document.getElementById('admin-dashboard');

// Backend API URL
const API_URL = 'https://your-backend-url.com'; // Replace with your actual backend URL

// Event Listeners
speakerForm.addEventListener('submit', handleSpeakerSubmission);
adminLoginBtn.addEventListener('click', openAdminLoginModal);
closeModalBtn.addEventListener('click', closeAdminLoginModal);
adminLoginForm.addEventListener('submit', handleAdminLogin);

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
            const submission = await response.json();
            addSubmissionToList(createSubmissionElement(submission));
            speakerForm.reset();
            alert('Speaker submitted successfully!');
        } else {
            throw new Error('Failed to submit speaker');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to submit speaker. Please try again.');
    }
}

function createSubmissionElement(submission) {
    const submissionElement = document.createElement('div');
    submissionElement.className = 'submission';
    submissionElement.innerHTML = `
        <h3>${submission.name}</h3>
        <p><strong>Expertise:</strong> ${submission.expertise}</p>
        <p><strong>Background:</strong> ${submission.background}</p>
        <p><strong>Status:</strong> ${submission.status}</p>
    `;
    return submissionElement;
}

function addSubmissionToList(submissionElement) {
    if (speakerSubmissions.firstChild.tagName === 'P') {
        speakerSubmissions.innerHTML = '';
    }
    speakerSubmissions.appendChild(submissionElement);
}

function openAdminLoginModal() {
    adminLoginModal.classList.remove('hidden');
}

function closeAdminLoginModal() {
    adminLoginModal.classList.add('hidden');
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
            closeAdminLoginModal();
            document.getElementById('speaker-submission').classList.add('hidden');
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
            speakerSubmissions.innerHTML = '';
            submissions.forEach(submission => {
                addSubmissionToList(createSubmissionElement(submission));
            });
        } else {
            throw new Error('Failed to load submissions');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to load submissions. Please try again.');
    }
}

// Initial load of admin dashboard if admin is logged in
if (localStorage.getItem('adminToken')) {
    document.getElementById('speaker-submission').classList.add('hidden');
    adminDashboard.classList.remove('hidden');
    loadAdminDashboard();
}