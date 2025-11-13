document.addEventListener("DOMContentLoaded", function () {
  const buildingId = document.getElementById("id_building_id");
  const floor = document.getElementById("id_floor");

  buildingId.onchange = (event) => {
    console.log(event.target.value)
    let children = Array.from(event.target.children);
    children.forEach((n) => {
      let n_floors = n.getAttribute('n_floors');
      if (event.target.value === n.value) {
        floor.innerHTML = '';
        for (let i = 0; i < n_floors; i++) {
          let opt = document.createElement('option');
          opt.value = i;
          opt.innerText = `${i + 1} andares`;
          floor.appendChild(opt);
        }
      }
    });
  }
});
