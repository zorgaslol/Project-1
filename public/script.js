console.log("script loaded")
const form = document.getElementById("taskForm");
const taskList = document.getElementById("taskList");

form.addEventListener("submit", fetchData);


async function fetchData(event){
    event.preventDefault();

    const title = form.querySelector("#pavadinimas").value.trim();
    const desc = form.querySelector("#aprasymas").value.trim();


    if(!title || !desc){
      alert("Prasau uzpildyti abu laukus");
      return;
    }
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
    li.classList.add("mainCard");
    li.innerHTML = `
        <div class="topCard">${data.title}</div>
                                    <div class="bottomCard">
                                        <div class="leftCard">${data.description}</div>
                                        <div class="rightCard"><button class="delete-btn">Delete</button></div>
                                    </div>
    `;
    taskList.append(li);
    form.reset();

}

taskList.addEventListener("click", async (e) => {
  if (!e.target.classList.contains("delete-btn")) return;

  const li = e.target.closest(".mainCard");
  const title = li.querySelector(".topCard").textContent;
  const description = li.querySelector(".leftCard").textContent;

  const fd = new FormData();
  fd.append("pavadinimas", title);
  fd.append("aprasymas", description);
  fd.append("delete", "true");

  const res = await fetch("index.php", {
    method: "POST",
    body: fd,
    headers: { "X-Requested-With": "fetch" }
  });

  const result = await res.json();
  if (result.success) li.remove();
});

