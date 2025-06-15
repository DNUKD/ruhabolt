document.addEventListener("DOMContentLoaded", () => {
  const tipusSelect = document.getElementById("tipus_id");
  const nemSelect = document.getElementById("nem");
  const arInput = document.getElementById("arInput");
  const arSlider = document.getElementById("arSlider");
  const arHiba = document.getElementById("arHiba");
  const arTartSzoveg = document.getElementById("arTartomanySzoveg");

  const arTartomanyok = {
    "pulcsi": [5000, 20000],
    "póló": [3500, 10000],
    "ruha": [8000, 90000],
    "szoknya": [6000, 25000],
    "nadrág": [6000, 20000],
    "body": [4000, 20000],
    "ing": [6000, 20000],
    "blúz": [8000, 20000],
  };

  if (tipusSelect) {
    tipusSelect.addEventListener("change", async () => {
      const tipusId = tipusSelect.value;
      const tipusNev = tipusSelect.options[tipusSelect.selectedIndex].text.trim();

      try {
        const fazonRes = await fetch(`./start.php?ajax=get_fazonok&tipus_id=${tipusId}`);
        const fazonok = await fazonRes.json();
        renderFazonok(fazonok);

        const hosszRes = await fetch(`./start.php?ajax=get_hosszak&tipus_id=${tipusId}`);
        const hosszak = await hosszRes.json();
        renderHosszak(hosszak);
      } catch (error) {
        console.error("Hiba az adatok betöltésekor:", error);
      }

      if (arTartomanyok[tipusNev]) {
        const [min, max] = arTartomanyok[tipusNev];
        if (arInput && arSlider && arTartSzoveg) {
          arInput.min = min;
          arInput.max = max;
          arInput.placeholder = `${min} – ${max}`;
          arSlider.min = min;
          arSlider.max = max;
          arSlider.value = min;
          arTartSzoveg.textContent = `Érvényes ár: ${min} – ${max} Ft`;

          arInput.addEventListener("input", () => {
            const ertek = parseInt(arInput.value);
            if (isNaN(ertek)) {
              arHiba.textContent = "";
            } else if (ertek < min || ertek > max) {
              arHiba.textContent = `Az árnak ${min} és ${max} Ft között kell lennie!`;
            } else {
              arHiba.textContent = "";
            }
            arSlider.value = ertek;
          });

          arSlider.addEventListener("input", () => {
            arInput.value = arSlider.value;
            arHiba.textContent = "";
          });
        }
      } else {
        if (arInput && arSlider && arTartSzoveg) {
          arInput.value = "";
          arSlider.value = 0;
          arTartSzoveg.textContent = "Válassz típust az árintervallumhoz.";
          arHiba.textContent = "";
        }
      }

  const meretSelect = document.getElementById("meret");
  if (meretSelect && nemSelect) {
    try {
      const res = await fetch(`./start.php?ajax=get_meretek&nem_id=${nemSelect.value}`);
      const meretek = await res.json();
      const aktualis = meretSelect.value;
      let benneVan = false;
  
      meretSelect.innerHTML = '<option value="">--</option>';
      meretek.forEach(m => {
        const opt = document.createElement("option");
        opt.value = m.meret;
        opt.textContent = m.meret;
        if (m.meret === aktualis) {
          opt.selected = true;
          benneVan = true;
        }
        meretSelect.appendChild(opt);
      });
  
      if (!benneVan) {
        meretSelect.value = ""; 
      }
    } catch (error) {
      console.error("Méret betöltés hiba:", error);
    }
  }
  
    });
  }

  if (nemSelect) {
    nemSelect.addEventListener("change", async () => {
      const nem = nemSelect.value;
      try {
        const res = await fetch(`./start.php?ajax=get_tipusok&nem_id=${nem}`);
        const text = await res.text();
        console.log("Válasz JSON:", text);

        const tipusok = JSON.parse(text);
        renderTipusok(tipusok);
      } catch (error) {
        console.error("Nem szerinti típusok betöltése sikertelen:", error);
      }
    });

    if (nemSelect.value) {
      nemSelect.dispatchEvent(new Event("change"));
    }
  }

  const arGomb = document.getElementById("nincsArGomb");
  if (arGomb && arInput) {
    arGomb.addEventListener("click", function () {
      if (arInput.disabled) {
        arInput.disabled = false;
        this.textContent = "Nincs ár";
        arInput.placeholder = arInput.dataset.placeholder || "Írd be az árat";
      } else {
        arInput.dataset.placeholder = arInput.placeholder;
        arInput.value = "";
        arInput.disabled = true;
        this.textContent = "Ár megadása";
      }
    });
  }

  const keszletInput = document.getElementById("keszlet");
  const keszletGomb = document.getElementById("nincsKeszletGomb");
  if (keszletGomb && keszletInput) {
    keszletGomb.addEventListener("click", function () {
      if (keszletInput.disabled) {
        keszletInput.disabled = false;
        this.textContent = "Nincs készleten";
      } else {
        keszletInput.value = 0;
        keszletInput.disabled = true;
        this.textContent = "Készlet megadása";
      }
    });
  }

  function renderTipusok(tipusok) {
    const select = document.getElementById("tipus_id");
    if (!select) return;
  
    const eredeti = document.getElementById("tipus_eredeti_id")?.value;
    let benneVan = false;
  
    select.innerHTML = '<option value="">-- Válassz típust --</option>';
    tipusok.forEach(t => {
      const option = document.createElement("option");
      option.value = t.id;
      option.textContent = t.nev;
      if (eredeti && t.id == eredeti) {
        option.selected = true;
        benneVan = true;
      }
      select.appendChild(option);
    });
  
    if (!benneVan && tipusok.length > 0) {
      select.value = tipusok[0].id;
    }
  
    select.dispatchEvent(new Event("change"));
  }
  
  function renderFazonok(fazonok) {
    const select = document.getElementById("fazon_id");
    if (!select) return;
  
    const eredeti = select.dataset.eredeti;
    select.innerHTML = '';
  
    if (fazonok.length === 0) {
      const option = document.createElement("option");
      option.value = "";
      option.textContent = "– nincs –";
      option.selected = true;
      select.appendChild(option);
      select.disabled = true;
    } else {
      select.disabled = false;
      select.innerHTML = '<option value="">-- Válassz fazont --</option>';
      fazonok.forEach(f => {
        const option = document.createElement("option");
        option.value = f.id;
        option.textContent = f.nev;
        if (eredeti && f.id == eredeti) {
          option.selected = true;
        }
        select.appendChild(option);
      });
    }
  }
  
  function renderHosszak(hosszak) {
    const select = document.getElementById("hossz_id");
    if (!select) return;
  
    const eredeti = select.dataset.eredeti;
    select.innerHTML = '';
  
    if (hosszak.length === 0) {
      const option = document.createElement("option");
      option.value = "";
      option.textContent = "– nincs –";
      option.selected = true;
      select.appendChild(option);
      select.disabled = true;
    } else {
      select.disabled = false;
      select.innerHTML = '<option value="">-- Válassz hosszt --</option>';
      hosszak.forEach(h => {
        const option = document.createElement("option");
        option.value = h.id;
        option.textContent = h.nev;
        if (eredeti && h.id == eredeti) {
          option.selected = true;
        }
        select.appendChild(option);
      });
    }
  }

});
