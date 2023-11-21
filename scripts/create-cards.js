function setCookie(cookieName, cookieValue, expirationDays) {
  const d = new Date();
  d.setTime(d.getTime() + (expirationDays * 24 * 60 * 60 * 1000));
  const expires = 'expires=' + d.toUTCString();
  document.cookie = cookieName + '=' + cookieValue + '; ' + expires + '; path=/';
}

function getHost() {
	let hostname = window.location.hostname;
	if (hostname == "localhost")
		hostname += "/dndinittracker";
	return hostname;
}

function fetch() {
	return new Promise((resolve, reject)=> {
		const xhr = new XMLHttpRequest();

		xhr.onreadystatechange = function() {
			if (xhr.readyState === 4) {
					if (xhr.status === 200) {
							resolve(xhr.responseText);
					} else {
							reject(new Error(`HTTP request failed with status ${xhr.status}`));
					}
			}
		}

		xhr.open('GET', 'http://'+getHost()+'/scripts/get-players.php', true);
		xhr.send();
	});
}	

function sleep(ms) {
	return new Promise(resolve => setTimeout(resolve, ms));
}

async function addcards() {
	try {
		const data = JSON.parse(await fetch());
		data.results.forEach((value) => {
			let card = document.createElement("div");
			card.className = "player";

			let name = document.createElement("h1");
			name.innerHTML = value.name;
			card.appendChild(name);

			let lvl = document.createElement("h1");
			lvl.innerHTML = "Level " + value.level;
			card.appendChild(lvl);

			let maxhp = document.createElement("h2");
			maxhp.innerHTML = "Max HP " + value.maxhp;
			card.appendChild(maxhp);

			let ac = document.createElement("h2");
			ac.innerHTML = "AC " + value.armorclass;
			card.appendChild(ac);

			let pperc = document.createElement("h3");
			pperc.innerHTML = " Perc. <br> " + value.passives.perception;
			card.appendChild(pperc);

			let pinv = document.createElement("h3");
			pinv.innerHTML = " Invs. <br> " + value.passives.investigation;
			card.appendChild(pinv);

			let pins = document.createElement("h3");
			pins.innerHTML = " Insi. <br> " + value.passives.insight;
			card.appendChild(pins);

			let pdv = document.createElement("h3");
			pdv.innerHTML = " DV. <br> " + value.passives.darkvision + "ft";
			card.appendChild(pdv);

			if (window.location.pathname == "/characters/" || window.location.pathname == "/dndinittracker/characters/") {
				let button = document.createElement("div");
				button.className = "custom-button";
				button.style = "width:200px;height:50px;padding:10px";

				let header = document.createElement("h2");
				header.innerHTML= "Select " + value.name;
				button.appendChild(header);

				card.addEventListener('click', function(event) {
					setCookie("selected", value.name, 30);
					location.reload();
				});

				card.appendChild(button);

			} else {
				card.style="height:460px";
			}

			document.getElementById("player-cards").appendChild(card);
		});

	} catch (error) {
		console.error('Error fetching data:', error);
	}
}

addcards();