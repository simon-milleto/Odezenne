// Set messages for form validation

const validationMessage = {
  en: {
    custom: {
      firstName: {
        required: 'Le prénom est obligatoire',
        alpha_dash: 'Veuillez saisir seulement des caractères alphabétiques',
      },
      lastName: {
        required: 'Le nom est obligatoire',
        alpha_dash: 'Veuillez saisir seulement des caractères alphabétiques',
      },
      email: {
        required: 'L\'adresse mail est obligatoire',
        email: 'Veuillez saisir une adresse mail valide',
      },
      phoneNumber: {
        required: 'Le numéro de téléphone est obligatoire',
        numeric: 'Veuillez saisir un numéro de valide',
      },
      city: {
        required: 'La ville est obligatoire',
        alpha_dash: 'Veuillez saisir seulement des caractères alphabétiques',
      },
      address: {
        required: 'L\'adresse est obligatoire',
      },
      postcode: {
        required: 'Le code postal est obligatoire',
        digits: 'Veuillez saisir seulement 5 chiffres',
      },
    },
  },
};

export default validationMessage;
