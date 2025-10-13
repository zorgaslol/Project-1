console.log("script loaded")
const form = document.getElementById("taskForm");
const taskList = document.getElementById("taskList");

form.addEventListener("submit", fetchData);


async function fetchData(event){
    event.preventDefault();
    console.log("JS is working")
    const fd = new FormData(form);

    const response = await fetch("index.php", {
        method: "POST",
        body: fd,
        headers: {
            'X-Requested-With' : "fetch"
        }
    });

    const data = await response.json();

    const li = document.createElement("li");
    li.innerHTML = `
        <h3>${data.title}</h3
        <p>${data.description}</p>
        <button class="delete-btn">Delete</button>
    `;
    taskList.append(li);
    form.reset();

}

taskList.addEventListener("click", async (e) => {
  if (!e.target.classList.contains("delete-btn")) return;

  const li = e.target.closest("li");
  const title = li.querySelector("h3").textContent;
  const description = li.querySelector("p").textContent;

  const fd = new FormData();
  fd.append("title", title);
  fd.append("description", description);
  fd.append("delete", "true");

  const res = await fetch("index.php", {
    method: "POST",
    body: fd,
    headers: { "X-Requested-With": "fetch" }
  });

  const result = await res.json();
  if (result.success) li.remove();
});

