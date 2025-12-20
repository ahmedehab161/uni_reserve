let slots = JSON.parse(localStorage.getItem("slots")) || [];

function save() {
  localStorage.setItem("slots", JSON.stringify(slots));
}

function generateSlots(start, end) {
  let result = [];
  let current = new Date(`1970-01-01T${start}`);
  let finish = new Date(`1970-01-01T${end}`);

  while (current < finish) {
    let next = new Date(current.getTime() + 15 * 60000);
    result.push(
      `${current.toTimeString().slice(0, 5)} - ${next
        .toTimeString()
        .slice(0, 5)}`
    );
    current = next;
  }
  return result;
}

function updateUI() {
  const table = document.getElementById("hallTable");
  const select = document.getElementById("slotSelect");
  const dateFilter = document.getElementById("filterDate");

  const selectedDate = dateFilter ? dateFilter.value : null;

  if (table) table.innerHTML = "";
  if (select) select.innerHTML = "";

  let availableSlots = slots.filter((s) => !s.booked);
  if (selectedDate) {
    availableSlots = availableSlots.filter((s) => s.date === selectedDate);
  }

  availableSlots.forEach((s) => {
    if (table) {
      table.innerHTML += `
                <tr>
                    <td>${s.hall}</td>
                    <td>${s.capacity}</td>
                    <td>${s.date}</td>
                    <td>${s.time}</td>
                </tr>`;
    }
    if (select) {
      select.innerHTML += `
                <option value="${s.id}">
                    ${s.hall} | ${s.date} | ${s.time}
                </option>`;
    }
  });

  const bookedCount = document.getElementById("bookedCount");
  const availableCount = document.getElementById("availableCount");

  if (bookedCount)
    bookedCount.textContent = slots.filter((s) => s.booked).length;
  if (availableCount) availableCount.textContent = availableSlots.length;
}

/* Admin add availability */
const addHallForm = document.getElementById("addHallForm");
if (addHallForm) {
  addHallForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const name = hallName.value;
    const capacity = hallCapacity.value;
    const date = slotDate.value;
    const times = generateSlots(startTime.value, endTime.value);

    times.forEach((time) => {
      slots.push({
        id: Date.now() + Math.random(),
        hall: name,
        capacity,
        date,
        time,
        booked: false,
      });
    });

    save();
    updateUI();
    addHallForm.reset();
    // alert("Availability added successfully");
  });
}

/* Booking */
const bookingForm = document.getElementById("bookingForm");
if (bookingForm) {
  bookingForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const slot = slots.find((s) => s.id == slotSelect.value);
    if (slot) slot.booked = true;
    save();
    updateUI();
    // alert("Booking confirmed");
  });
}

const filterDate = document.getElementById("filterDate");
if (filterDate) {
  filterDate.addEventListener("change", updateUI);
}

setInterval(updateUI, 1000);
updateUI();
