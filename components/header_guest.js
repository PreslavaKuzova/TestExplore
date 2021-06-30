const headerTemplate = document.createElement('template');
headerTemplate.innerHTML = `
  <style>
    nav {
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color:  #1397D5;
    }
    a {
      font-weight: 700;
      margin: 0 25px;
      color: #fff;
      text-decoration: none;
    }
    
    a:hover {
      padding-bottom: 5px;
      box-shadow: inset 0 -2px 0 0 #fff;
    }
    
  </style>
  <header>
    <nav>
        <a href="/Home"><img href="/Home" src="/img/logo_size_invert.jpg" alt="logo" /></a>
        <div class="nav-content">
            <a href="/Home">Introduction</a>
            <a href="/Register">Register</a>
            <a href="/Login">Login</a>
        </dvi>
    </nav>
  </header>
`;

class Header_guest extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        const shadowRoot = this.attachShadow({ mode: 'closed' });
        shadowRoot.appendChild(headerTemplate.content);
    }
}

customElements.define('header-component', Header_guest);