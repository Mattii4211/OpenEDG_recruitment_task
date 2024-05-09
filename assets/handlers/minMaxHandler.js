import showModal from "./modalHandlers";

const url = window.location.href.split('?')[0] + 'checkMinMax';
const form = document.querySelector('[name="getMinMax"]');
const minInput = document.querySelector('[name="min"]');
const maxInput = document.querySelector('[name="max"]');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = new FormData(e.target);
    const dataToSend = {};
    for (const [key, value] of data) {
        dataToSend[key] = value;
    }

    if (checkCorrectPeriod(dataToSend.period, dataToSend.range)) {
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
            minInput.value = data.min;
            maxInput.value = data.max;

        } catch (error) {
            showModal('Error', error);
        }
    } else {
        showModal('Error', 'Incorrect period range!');
    }
});


function checkCorrectPeriod(period, range) {
    switch (period) {
        case 'week':
            return range > 0 && range <= 52;
        case 'month':
            return range > 0 && range <= 12;
        case 'quarter':
            return range > 0 && range <= 4;
    }
    return false;
}