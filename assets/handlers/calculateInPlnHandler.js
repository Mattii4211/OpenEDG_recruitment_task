import showModal from "./modalHandlers";

const url = window.location.href.split('?')[0] + 'calculateInPln';
const form = document.querySelector('[name="getPlnValue"]');
const valueInPlnInput = document.querySelector('[name="valueInPln"]');


form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = new FormData(e.target);
    const dataToSend = {};
    for (const [key, value] of data) {
        dataToSend[key] = value;
    }

    try {
        const response = await fetch(
            url,
            {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(dataToSend)
            }
        );
        const data = await response.json();
        valueInPlnInput.value = data.value;
    } catch (error) {
        showModal('Error', error);
    }
});
