function getCookie(name) {
  const cookieString = document.cookie;
  const cookies = cookieString.split('; ');

  for (const cookie of cookies) {
    const [cookieName, cookieValue] = cookie.split('=');
    if (cookieName === name) {
      return cookieValue;
    }
  }

  return null; // Cookie not found
}

function fetchData(id) {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          resolve(xhr.responseText);
        } else {
          reject(new Error(`HTTP request failed with status ${xhr.status}`));
        }
      }
    };

    xhr.open('POST', '../scripts/loadcharview.php', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("name="+id.replaceAll("-"," "));
  });
}

async function genCard(id) {
    try {
        const data = (await fetchData(id)).split("\\");

        let characterstats = document.createElement("div");
        characterstats.class = "msg-box";
        characterstats.style = "display:inline-block;vertical-align:top;";

        let charname = document.createElement("h1");
        charname.innerHTML = data[0];
        characterstats.appendChild(charname);

        let intrinsics = document.createElement("p");
        intrinsics.innerHTML = "level "+data[1]+"<br>"+data[2]+" max hp&emsp;"+data[3]+" AC<br>passives:<br>"+data[4]+" perception&emsp;"+data[5]+" investigation<br>"+data[6]+" insight&emsp;Darkvision "+data[7]+"ft";
        characterstats.appendChild(intrinsics);
        

        let tempstats = document.createElement("div");
        tempstats.class = "msg-box";
        tempstats.style = "display:inline-block;vertical-align:top;";

        let party = document.createElement("h1");
        party.innerHTML = "Party: HEARTS OF IRON";
        tempstats.appendChild(party);

        if (data[9] != 0) {
          let battlstat = document.createElement("p");
          battlstat.innerHTML = data[8]+"/"+data[2]+"hp<br>Currently in battle?<br>TRUE, initiative:"+data[9]+"<br>Conditions:<br>";
          tempstats.appendChild(battlstat);
        } else {
          let battlstat = document.createElement("p");
          battlstat.innerHTML = data[2]+"/"+data[2]+"hp<br>Currently in battle?<br>False<br>Conditions:<br>Not currently affected by any conditions.";
          tempstats.appendChild(battlstat);
        }

        let partystats = document.createElement("div");
        partystats.class = "msg-box";
        partystats.style = "display:inline-block;vertical-align:top;";

        let partyname = document.createElement("h1");
        partyname.innerHTML = "Hearts of Iron";
        partystats.appendChild(partyname);

        let members = document.createElement("p");
        members.innerHTML = "Members ("+data[12]+"):<br>";
        partystats.appendChild(members);

        let memlist = document.createElement("div");
        memlist.style = "text-align:left;position:relative;left:40px";
        memlist.innerHTML = "";
        partystats.appendChild(memlist);

        data[11].split("/").forEach((value, index)=>{
          let lvl = "";
          let name = "";
          for (let i = 0; i < value.length; i++) {
            let char = value[i];
            if (!isNaN(char)) {
              lvl+=char;
            } else {
              name = value.substring(i);
              break;
            }
          }
          if (memlist.innerHTML != "") {
            if (index % 3 == 0)
              memlist.innerHTML += ",<br>";
            else
              memlist.innerHTML += ", ";
          }
          memlist.innerHTML+=index + name + "(lv." + lvl + ")"
        });

        let ttlpart = document.createElement("p");
        ttlpart.innerHTML = "Total HP<br>"+data[13]+"<br>Average Level ("+data[14]+")";
        partystats.appendChild(ttlpart);

        let img = document.createElement("img");
        img.src = "../resources/hoi128.png";
        img.alt = "Party Emblem";
        img.style = "position:relative;left:50px;top:20px";

        document.getElementById("view-current-player").appendChild(characterstats);
        document.getElementById("view-current-player").appendChild(tempstats);
        document.getElementById("view-current-player").appendChild(partystats);
        document.getElementById("view-current-player").appendChild(img);
    } catch (error) {
        console.error('Error fetching data:', error);
        // Handle the error as needed
    }
}


function clickHandler(event) {
  // remove buttons b4 adding char
  const item = document.querySelectorAll('.custom-button');
  item.forEach(function(item) {
    item.remove();
  })
  var id = event.currentTarget.id.substring(7).replaceAll("-", " ");

  genCard(id);
  editForm(id);
}

function editForm(id) {

  let editor = document.createElement("div");
  editor.className="msg-box";
  editor.style="justify-content: center; display: block;margin-left: auto; margin-right: auto;width:600px";

  let header = document.createElement("h2");
  header.innerHTML = "Edit character..."
  editor.appendChild(header);

  let form = document.createElement("form");
  form.action = "../scripts/updatechar.php";
  form.method = "post";

  let hidden = document.createElement("input");
  hidden.id = "selected";
  hidden.type="hidden";
  hidden.name="selected";
  hidden.value=id;
  form.appendChild(hidden);

  let namelbl = document.createElement("label");
  namelbl.htmlFor = "name";
  namelbl.innerHTML = "Name: ";
  let name = document.createElement("input");
  name.id = "name";
  name.type="text";
  name.name="name";
  name.placeholder="Name";
  form.appendChild(namelbl);
  form.appendChild(name);

  form.appendChild(document.createElement("br"));

  let levellbl = document.createElement("label");
  levellbl.htmlFor = "level";
  levellbl.innerHTML = "Level: ";
  let level = document.createElement("input");
  level.id = "level";
  level.type="number";
  level.name="level";
  level.style="width:40px";
  level.min="0";
  level.step="1";
  form.appendChild(levellbl);
  form.appendChild(level);

  let maxhplbl = document.createElement("label");
  maxhplbl.htmlFor = "maxhp";
  maxhplbl.innerHTML = "Max HP: ";
  let maxhp = document.createElement("input");
  maxhp.id = "maxhp";
  maxhp.type="number";
  maxhp.name="maxhp";
  maxhp.style="width:40px";
  maxhp.min="0";
  maxhp.step="1";
  form.appendChild(maxhplbl);
  form.appendChild(maxhp);

  let aclbl = document.createElement("label");
  aclbl.htmlFor = "ac";
  aclbl.innerHTML = "Armor Class: ";
  let ac = document.createElement("input");
  ac.id = "ac";
  ac.type="number";
  ac.name="ac";
  ac.style="width:40px";
  ac.min="0";
  ac.step="1";
  form.appendChild(aclbl);
  form.appendChild(ac);

  form.appendChild(document.createElement("br"));

  let passperceptionlbl = document.createElement("label");
  passperceptionlbl.htmlFor = "passperception";
  passperceptionlbl.innerHTML = "Passive Perception: ";
  let passperception = document.createElement("input");
  passperception.id = "passperception";
  passperception.type="number";
  passperception.name="passperception";
  passperception.style="width:40px";
  passperception.min="0";
  passperception.step="1";
  form.appendChild(passperceptionlbl);
  form.appendChild(passperception);

  let passinvestigationlbl = document.createElement("label");
  passinvestigationlbl.htmlFor = "passinvestigation";
  passinvestigationlbl.innerHTML = "Passive Investigation: ";
  let passinvestigation = document.createElement("input");
  passinvestigation.id = "passinvestigation";
  passinvestigation.type="number";
  passinvestigation.name="passinvestigation";
  passinvestigation.style="width:40px";
  passinvestigation.min="0";
  passinvestigation.step="1";
  form.appendChild(passinvestigationlbl);
  form.appendChild(passinvestigation);

  form.appendChild(document.createElement("br"));

  let passinsightlbl = document.createElement("label");
  passinsightlbl.htmlFor = "passinsight";
  passinsightlbl.innerHTML = "Passive Insight: ";
  let passinsight = document.createElement("input");
  passinsight.id = "passinsight";
  passinsight.type="number";
  passinsight.name="passinsight";
  passinsight.style="width:40px";
  passinsight.min="0";
  passinsight.step="1";
  form.appendChild(passinsightlbl);
  form.appendChild(passinsight);

  let darkvisionlbl = document.createElement("label");
  darkvisionlbl.htmlFor = "darkvision";
  darkvisionlbl.innerHTML = "Darkvision: ";
  let darkvision = document.createElement("input");
  darkvision.id = "darkvision";
  darkvision.type="number";
  darkvision.name="darkvision";
  darkvision.style="width:40px";
  darkvision.min="0";
  darkvision.step="1";
  form.appendChild(darkvisionlbl);
  form.appendChild(darkvision);

  form.appendChild(document.createElement("br"));

  let submit = document.createElement("button");
  submit.type = "submit";
  submit.name = "submit";
  submit.innerHTML = "Submit";
  form.appendChild(submit);

  form.appendChild(document.createElement("br"));
  form.appendChild(document.createElement("br"));
  form.appendChild(document.createElement("br"));

  let delet = document.createElement("button");
  delet.type = "submit";
  delet.name = "delete";
  delet.innerHTML = "DELETE CHARACTER";
  form.appendChild(delet);

  editor.appendChild(form);

  document.getElementById("edit-player-card").appendChild(editor);
}

if (getCookie("loggedperms") === "dm") {

  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let id = xhr.responseText.split("/");
      id.forEach(function(val) {
        let button = document.createElement("div");
        button.className = "custom-button";
        button.style = "display:inline-block"
        button.id="player-"+val.replaceAll(" ", "-");
        let head = document.createElement("h2");
        head.innerHTML = val;
        button.appendChild(head);

        button.addEventListener('click', clickHandler);

        let section = document.getElementById("view-current-player");
        section.append(button)
      });
    }
  }
  xhr.open("GET", "../scripts/getchars.php", true); // Replace with your server endpoint
  xhr.send();
}