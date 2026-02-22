let string = ""
let result, h = "", r2, arr = [""], new_arr;
let buttons = document.querySelectorAll('.button')
let history = document.querySelector('.calculations')
Array.from(buttons).forEach((button) => {
  button.addEventListener('click', (e) => {
    if (e.target.innerHTML == '=') {
      result = eval(string)
      r2 = h + "=" + result + "\n"
      arr.push(r2)
      document.querySelector('.output').value = result
      document.querySelector('.calculations').value = r2
    }
    else if (e.target.innerHTML == 'Clear') {
      string = ""
      document.querySelector('.output').value = string
      document.querySelector('.input').value = string
      arr.push("Cleared\n")
      // document.querySelector('.calculations').value = r2+'Cleared'
    }
    else if (e.target.innerHTML == 'Del') {
      string = string.slice(0, -1)
      h = string
      document.querySelector('.input').value = string
      // document.querySelector('.calculations').value = string
    }
    else {
      string = string + e.target.innerHTML
      document.querySelector('.input').value = string
      h = string
      document.querySelector('.calculations').value = string
    }
    new_arr = arr.join("->")
    document.querySelector('.calculations').value = new_arr
  })
})
