// ( async () => {
//     const url = "http://ws4cjdg.com/OPERACION_GUZMAN/refrigerado.json";
//     try {
//         const response = await fetch(url);
//         console.log(response);
        

//     if (!response.ok) {
//       throw new Error("Error en la petición");
//     }

//     const data = await response.json();
//     console.log(data);

//   } catch (error) {
//     console.error("Error:", error);
//   }
// } )

document.addEventListener("DOMContentLoaded", async () => {
    const url = "http://ws4cjdg.com/OPERACION_GUZMAN/refrigerado.json";
    try {
        const response = await fetch(url);
        console.log(response);
        

    if (!response.ok) {
      throw new Error("Error en la petición");
    }

    const data = await response.json();
    console.log(data);

  } catch (error) {
    console.error("Error:", error);
  }
});
