using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Security.Cryptography;
using System.Security.Policy;
using System.Text;
using System.Web;

namespace RestSem3SystemMockBeerTest.Models
{
    public class Crypto
    {

        private byte[] saltBytes;
        private int keySize;
        private int blockSize;

        public Crypto()
        {
            saltBytes = new byte[] { 1, 2, 3, 4, 5, 6, 7, 8 };
            blockSize = 128;
            keySize = 256;
        }

        /// <summary>
        /// This will encrypt string values. This method is URL friendly,
        /// so the return will replace the +/= characters - this feature
        /// will be handled and corrected in the Decrypt method.
        /// </summary>
        /// <param name="toEncrypt">The string to encrypt</param>
        /// <param name="pw">The password to encrypt with</param>
        /// <returns>(string) The encrypted value</returns>
        public string Encrypt(string toEncrypt, string pw)
        {
            byte[] encBytes = Encoding.UTF8.GetBytes(toEncrypt);
            byte[] pwBytes = Encoding.UTF8.GetBytes(pw);

            pwBytes = SHA256.Create().ComputeHash(pwBytes);

            using (MemoryStream ms = new MemoryStream())
            using (Rijndael aes = new RijndaelManaged())
            {
                aes.KeySize = keySize;
                aes.BlockSize = blockSize;

                Rfc2898DeriveBytes key = new Rfc2898DeriveBytes(pwBytes, saltBytes, 1000);
                aes.Key = key.GetBytes(aes.KeySize / 8);
                aes.IV = key.GetBytes(aes.BlockSize / 8);

                aes.Mode = CipherMode.CBC;

                using (CryptoStream cs = new CryptoStream(ms, aes.CreateEncryptor(), CryptoStreamMode.Write))
                {
                    cs.Write(encBytes, 0, encBytes.Length);
                    cs.Close();
                }

                encBytes = ms.ToArray();
            }

            string result = Convert.ToBase64String(encBytes);
            result = result.Replace("+", "-").Replace("/", "_").Replace("=", "~");
            return result;
        }

        /// <summary>
        /// This method will decrypt data with the same values, 
        /// that the Encrypt method encrypted the data with. 
        /// Just feed this method directly with the return from the 
        /// Encrypt method and the corresponding password, to have you
        /// data decrypted back to human readable text.
        /// </summary>
        /// <param name="toDecrypt">The encrypted value (as gotten from the Encrypt method)</param>
        /// <param name="pw">The password used to encrypt the data</param>
        /// <returns>(string) The text as was before the encryption</returns>
        public string Decrypt(string toDecrypt, string pw)
        {
            toDecrypt = toDecrypt.Replace("-", "+").Replace("_", "/").Replace("~", "=");
            byte[] decBytes = Convert.FromBase64String(toDecrypt);
            byte[] pwBytes = Encoding.UTF8.GetBytes(pw);
            pwBytes = SHA256.Create().ComputeHash(pwBytes);

            using (MemoryStream ms = new MemoryStream())
            using (RijndaelManaged aes = new RijndaelManaged())
            {
                aes.KeySize = keySize;
                aes.BlockSize = blockSize;

                Rfc2898DeriveBytes key = new Rfc2898DeriveBytes(pwBytes, saltBytes, 1000);
                aes.Key = key.GetBytes(aes.KeySize / 8);
                aes.IV = key.GetBytes(aes.BlockSize / 8);

                aes.Mode = CipherMode.CBC;

                using (CryptoStream cs = new CryptoStream(ms, aes.CreateDecryptor(), CryptoStreamMode.Write))
                {
                    cs.Write(decBytes, 0, decBytes.Length);
                    cs.Close();
                }

                decBytes = ms.ToArray();
            }

            string result = Encoding.UTF8.GetString(decBytes);
            return result;
        }
    }
}