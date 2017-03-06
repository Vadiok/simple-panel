export class PasswordHelper {

	public static generatePassword(passwordLength: number = 32) {
		let allowedChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		let allowedCharsLength = allowedChars.length;
		let result = "";
		for (let i = 0; i < passwordLength; i++) {
			let randChar = Math.floor(Math.random() * allowedCharsLength);
			result += allowedChars[randChar];
		}
		return result;
	}

}
