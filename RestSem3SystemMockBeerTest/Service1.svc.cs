using System;
using System.Collections;
using System.Collections.Generic;
using System.Data.SqlClient;
using System.Linq;
using System.Runtime.Serialization;
using System.Security.Cryptography;
using System.ServiceModel;
using System.ServiceModel.Security;
using System.ServiceModel.Web;
using System.Text;
using RestSem3SystemMockBeerTest.Models;

namespace RestSem3SystemMockBeerTest
{
    // NOTE: You can use the "Rename" command on the "Refactor" menu to change the class name "Service1" in code, svc and config file together.
    // NOTE: In order to launch WCF Test Client for testing this service, please select Service1.svc or Service1.svc.cs at the Solution Explorer and start debugging.
    public class Service1 : IService1
    {

        //private const string db ="Data Source=(localdb)\\MSSQLLocalDB;Initial Catalog=beersMockSem3;Integrated Security=True;Connect Timeout=30;Encrypt=False;TrustServerCertificate=True;ApplicationIntent=ReadWrite;MultiSubnetFailover=False";
        private const string db = "Server=tcp:beerinator.database.windows.net,1433;Initial Catalog=beerinator;Persist Security Info=False;User ID=user;Password=accessForAll678;MultipleActiveResultSets=False;Encrypt=True;TrustServerCertificate=False;Connection Timeout=30;";
        
        private const string salt = "RtbDihnLR5D8Y";
        private const string characters = "abcdefghijklmnopqrstuvwxyz0123456789";

        /// <summary>
        /// This will return the current amount of beers in the fridge
        /// </summary>
        /// <returns>string(int) fridge amount value from the database</returns>
        public string GetFridgeValue()
        {
            const string sql = "SELECT TOP 1 amount FROM beers ORDER BY id DESC";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand query = new SqlCommand(sql, conn))
            {
                conn.Open();
                using (SqlDataReader result = query.ExecuteReader())
                {
                    result.Read();
                    string val = result["amount"].ToString();
                    conn.Close();
                    return val;
                }
            }
        }

        /// <summary>
        /// This will return the schools total amount of beer (stocked)
        /// </summary>
        /// <returns>string(int) total beer value from the database</returns>
        public string GetTotalValue()
        {
            const string sql = "SELECT TOP 1 total FROM beers ORDER BY id DESC";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand query = new SqlCommand(sql, conn))
            {
                conn.Open();
                using (SqlDataReader result = query.ExecuteReader())
                {
                    result.Read();
                    string val = result["total"].ToString();
                    conn.Close();
                    return val;
                }
            }
        }

        /// <summary>
        /// Update the fridge value in the database.
        /// This will be called everytime beers are placed/removed
        /// from the fridge. It will substract from the total value 
        /// as well, when you are removing beers from the fridge.
        /// </summary>
        /// <param name="value">int value of the new beer amount in the fridge</param>
        /// <returns>Will return 1 on success and -1 on fail.</returns>
        public int UpdateFridge(string token, string value)
        {
            // Validate token
            if (token != salt)
                return -1;

            // Get the latest values from the db
            int fridgeValue = int.Parse(GetFridgeValue());
            int totalValue = int.Parse(GetTotalValue());
            
            // Convert the string request value to int
            int newValue;
            if (!int.TryParse(value, out newValue))
                return -1;
            

            // If we remove beer from the fridge, subtract from fridge
            // and total value - else just subtract from fridge value.
            if (newValue < 0)
            {
                fridgeValue += newValue;
                totalValue += newValue;
            }
            else
            {
                fridgeValue = fridgeValue + newValue;
            }

            int dateTime = CreateDateTime();

            // Perform the query
            const string sql = "INSERT INTO beers (amount, total, dt) VALUES (@fridge, @total, @dt)";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand insert = new SqlCommand(sql, conn))
            {
                insert.Parameters.AddWithValue("@fridge", fridgeValue);
                insert.Parameters.AddWithValue("@total", totalValue);
                insert.Parameters.AddWithValue("@dt", dateTime);

                conn.Open();
                int rowsAffected = insert.ExecuteNonQuery();
                conn.Close();
                return rowsAffected;
            }
        }

        private int CreateDateTime()
        {
            // Build a date/time string
            string hour = DateTime.Now.Hour.ToString();
            string minute = DateTime.Now.Minute.ToString();
            string month = DateTime.Now.Month.ToString();
            string day = DateTime.Now.Day.ToString();
            string year = DateTime.Now.Year.ToString();
            string dateTimePlaceHolder = year + month + day + hour + minute;
            return int.Parse(dateTimePlaceHolder);
        }

        /// <summary>
        /// Login Method. Tries to match credentials with user profiles in the database.
        /// </summary>
        /// <param name="uname">The users E-mail (username)</param>
        /// <param name="pwHash">The hashed password - Hash it with salt before sending the request</param>
        /// <returns>Return the users token for login SESSION</returns>
        public Profile Login(string token, Profile profile)
        {
            if (token != salt)
                return null;

            if (profile.password == null || profile.uname == null)
                return null;

            string sql = "SELECT * FROM profiles WHERE uname=@uname AND upass=@upass";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand query = new SqlCommand(sql, conn))
            {
                query.Parameters.AddWithValue("@uname", profile.uname);
                query.Parameters.AddWithValue("@upass", profile.password);
                conn.Open();
                using (SqlDataReader result = query.ExecuteReader())
                {
                    // If user was NOT found, return an empty token
                    if (!result.HasRows)
                        return null;

                    result.Read();
                    Profile user = new Profile
                    {
                        fullname = result.GetString(2),
                        id = result.GetInt32(4),
                        role = result.GetInt32(5),
                        password = result.GetString(1)
                    };
                    conn.Close();
                    return user;
                }
            }
        }

        /// <summary>
        /// This is a public method to call the Crypto model.
        /// This will call its Encrypt method.
        /// </summary>
        /// <param name="value">The value to Encrypt</param>
        /// <param name="password">The password to encrypt with</param>
        /// <returns>The encrypted string</returns>
        public string Encrypt(string value, string password)
        {
            Crypto crypt = new Crypto();
            string result = crypt.Encrypt(value, password);
            return result;
        }

        /// <summary>
        /// This, as above, will call the Crypto model.
        /// Here we'll decrypt the data back to readable text.
        /// </summary>
        /// <param name="value">The encrypted string</param>
        /// <param name="password">The password used to encrypt the string</param>
        /// <returns>The string as before the encryption</returns>
        public string Decrypt(string value, string password)
        {
            Crypto crypt = new Crypto();
            string result = crypt.Decrypt(value, password);
            return result;
        }

        /// <summary>
        /// A little function used to generate random strings, used 
        /// for passwords. Note that this solution is not a very safe
        /// approach, so change the password when you log into your 
        /// new profile.
        /// </summary>
        /// <param name="length">Numeric length of the string to return</param>
        /// <returns>Random string</returns>
        private string RandomString(int length)
        {
            Random rand = new Random();
            string randomString = "";
            int randomNumber;
            for (int i = 0; i < length; i++)
            {
                randomNumber = rand.Next(1, characters.Length);
                randomString += characters[randomNumber];
            }
            return randomString;
        }

        /// <summary>
        /// A function that will hash a string with SHA256 
        /// </summary>
        /// <param name="value">The value to hash</param>
        /// <returns>The hashed value</returns>
        private string HashString(string value)
        {
            SHA256 hash = SHA256.Create();
            byte[] hashString = Encoding.ASCII.GetBytes(value);
            hashString = hash.ComputeHash(hashString);
            return Encoding.UTF8.GetString(hashString);
        }

        /// <summary>
        /// Creates a new profile in the database.
        /// </summary>
        /// <param name="token">The hardcoded token (peper)</param>
        /// <param name="uname">The new users Username</param>
        /// <param name="fullname">The new users Fullname</param>
        /// <param name="role">The role of the new user (1=PurchaseManager, 2=Admin)</param>
        /// <returns>The random generated password for the new user</returns>
        public string CreateProfile(string token, Profile profile)
        {
            // Match token with salt
            if (token != salt)
                return "NULL";
            if (profile == null)
                return "NULL";
            
            // Validate that the username is an email
            if (!validateEmail(profile.uname))
                return "Ugyldig brugernavn. Anvend en gyldig E-mail adresse.";

            // Check that we don't already have that email in our database
            if (mailExists(profile.uname))
                return "Brugeren eksistere allerede. Anvend en anden E-mail adresse.";

            // Check that there is a value in password and name
            if (profile.password == null || profile.fullname == null)
                return "Obligatoriske værdier: ['uname', 'password', 'fullname', 'role']";

            // Check that the role is either 1 or 2 (buyer/admin)
            if (profile.role != 1 && profile.role != 2)
                return "Anvend numerisk værdi for rollen: 1=indkøbschef, 2=administrator";

            // Perform the query
            const string sql = "INSERT INTO profiles (uname, upass, fullname, role) VALUES (@uname, @upass, @fullname, @role)";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand insert = new SqlCommand(sql, conn))
            {
                insert.Parameters.AddWithValue("@uname", profile.uname);
                insert.Parameters.AddWithValue("@upass", profile.password);
                insert.Parameters.AddWithValue("@fullname", profile.fullname);
                insert.Parameters.AddWithValue("@role", profile.role);

                conn.Open();
                int rowsAffected = insert.ExecuteNonQuery();
                conn.Close();
                if (rowsAffected == 1)
                    return "Profil Oprettet.";
                return "Der opstod fejl. Prøv igen.";
            }
        }

        /// <summary>
        /// Reads all the profiles from the database. 
        /// Will NOT return 'Token' and 'Password' (even though it's hashed).
        /// These values has no value for the individual user, so they are left
        /// for the backend instead.
        /// </summary>
        /// <param name="token">The hardcoded salt (peper)</param>
        /// <returns>All the profiles from the datase.</returns>
        public IList<Profile> GetAllProfiles(string token)
        {

            IList<Profile> result = new List<Profile>();

            // Return an empty list if token dont match
            if (token != salt)
                return result;

            // Perform the query
            const string sql = "SELECT * FROM profiles ORDER BY id DESC";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand query = new SqlCommand(sql, conn))
            {
                conn.Open();
                using (SqlDataReader reader = query.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        // Add the values to a Profile model
                        string uname = reader.GetString(0);
                        string fullname = reader.GetString(2);
                        int id = reader.GetInt32(4);
                        int role = reader.GetInt32(5);

                        // Add that model to our list
                        result.Add(new Profile
                        {
                            uname = uname,
                            fullname = fullname,
                            id = id,
                            role = role
                        });
                    }

                    // Close connection and return the list
                    conn.Close();
                    reader.Close();
                    return result;
                }
            }
        }

        /// <summary>
        /// This will delete a chosen profile from the database.
        /// </summary>
        /// <param name="token">The salt (peper)</param>
        /// <param name="id">The profile 'id' to delete</param>
        /// <returns>The number of rows affected</returns>
        public int DeleteProfile(string token, string id)
        {
            // Confirm token and check id is numeric
            int userId;
            if (token != salt || !int.TryParse(id, out userId))
                return -1;

            const string sql = "DELETE FROM profiles WHERE id=@id";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand insert = new SqlCommand(sql, conn))
            {

                insert.Parameters.AddWithValue("@id", userId);

                conn.Open();
                int rowsAffected = insert.ExecuteNonQuery();
                conn.Close();
                return rowsAffected;
            }
        }

        /// <summary>
        /// This will be a max value for maximum beer amount in their fridge.
        /// This value is used to calculate the percentage left in the fridge i.e.
        /// </summary>
        /// <param name="token">The salt (peper)</param>
        /// <param name="value">The max value of beers able to fit into the fridge</param>
        /// <returns>The number of rows affected</returns>
        public int UpdateFridgeMax(string token, string value)
        {
            // Confirm token and check value is numeric
            int newValue;
            if (token != salt)
                return -1;
            if (!int.TryParse(value, out newValue))
                return -1;
            
            // Run the query
            const string sql = "UPDATE settings SET maxFridge=@value";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand insert = new SqlCommand(sql, conn))
            {

                insert.Parameters.AddWithValue("@value", newValue);

                conn.Open();
                int rowsAffected = insert.ExecuteNonQuery();
                conn.Close();
                return rowsAffected;
            }
        }

        /// <summary>
        /// This will update the lowest amount of beers allowed in the fridge, 
        /// before a notification will be showned in the Beer-Inator animation.
        /// </summary>
        /// <param name="token">The salt (peper)</param>
        /// <param name="value">The lowest amount of beer allowed in the fridge before notifying</param>
        /// <returns></returns>
        public int UpdateFridgeMin(string token, string value)
        {
            // Confirm token and check value is numeric
            int newValue;
            if (token != salt)
                return -1;
            if (!int.TryParse(value, out newValue))
                return -1;

            // Run the query
            const string sql = "UPDATE settings SET minFridge=@minFridge";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand insert = new SqlCommand(sql, conn))
            {

                insert.Parameters.AddWithValue("@minFridge", newValue);

                conn.Open();
                int rowsAffected = insert.ExecuteNonQuery();
                conn.Close();
                return rowsAffected;
            }
        }

        /// <summary>
        /// This will update the lowest allowed amount of beers stock-piled,
        /// before notifying the purchase manager to buy new beers.
        /// </summary>
        /// <param name="token">The salt (peper)</param>
        /// <param name="value">The lowest value of beer stock-piled before notifying</param>
        /// <returns>The amount of rows affected</returns>
        public int UpdateNotifyMin(string token, string value)
        {
            // Confirm token and check value is numeric
            int newValue;
            if (token != salt)
                return -1;
            if (!int.TryParse(value, out newValue))
                return -1;

            // Run the query
            const string sql = "UPDATE settings SET minNotify=@value";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand insert = new SqlCommand(sql, conn))
            {

                insert.Parameters.AddWithValue("@value", newValue);

                conn.Open();
                int rowsAffected = insert.ExecuteNonQuery();
                conn.Close();
                return rowsAffected;
            }
        }

        /// <summary>
        /// Validates Email addresses. Usernames are required to be 
        /// Emails because of the notifications - therefore we want
        /// to be able to validate usernames.
        /// </summary>
        /// <param name="address">The E-mail address to validate</param>
        /// <returns>True/False</returns>
        private bool validateEmail(string address)
        {
            try
            {
                var addr = new System.Net.Mail.MailAddress(address);
                return addr.Address == address;
            }
            catch
            {
                return false;
            }
        }

        /// <summary>
        /// This will perform a check to see if an Email already exists in our database.
        /// This is used when we are creating new profiles - we don't want dublicates. 
        /// </summary>
        /// <param name="mail">The E-mail address to check against</param>
        /// <returns>True/False if it exists or not</returns>
        private bool mailExists(string mail)
        {
            string sql = "SELECT id FROM profiles WHERE uname=@uname";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand query = new SqlCommand(sql, conn))
            {
                query.Parameters.AddWithValue("@uname", mail);
                conn.Open();
                using (SqlDataReader result = query.ExecuteReader())
                {
                    bool value = result.HasRows;
                    conn.Close();
                    return value;
                }
            }
        }

        /// <summary>
        /// This will return ALL the beer values from the database.
        /// This will be used to create visual statistics for periods
        /// of time.
        /// </summary>
        /// <param name="token">The salt (peper)</param>
        /// <returns>All the values from the Beers table</returns>
        public IList<Beer> GetAllBeers()
        {

            IList<Beer> result = new List<Beer>();

            // Perform the query
            const string sql = "SELECT * FROM beers ORDER BY id DESC";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand query = new SqlCommand(sql, conn))
            {
                conn.Open();
                using (SqlDataReader reader = query.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        // Add the values to a Beer model
                        int amount = reader.GetInt32(1);
                        Int64 dateTime = reader.GetInt64(3);
                        int total = reader.GetInt32(2);

                        // Add that model to our list
                        result.Add(new Beer(amount, total, dateTime));
                    }

                    // Close connection and return the list
                    conn.Close();
                    reader.Close();
                    return result;
                }
            }
        }

        /// <summary>
        /// A method allowing users to change their password.
        /// This only updates the database, it doesn't generate the hash.
        /// Make sure you hash the password before sending the request.
        /// </summary>
        /// <param name="token">The salt (peper)</param>
        /// <param name="id">The profile 'id' to change the password for</param>
        /// <param name="newPasswordHash">The new password in a SHA256 hashed value</param>
        /// <returns>The amount of rows affected</returns>
        public int UpdatePassword(string token, Profile profile)
        {
            if (profile.id == 0 || profile.password == null)
                return -1;

            const string sql = "UPDATE profiles SET upass=@upass WHERE id=@id";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand insert = new SqlCommand(sql, conn))
            {

                insert.Parameters.AddWithValue("@id", profile.id);
                insert.Parameters.AddWithValue("@upass", profile.password);

                conn.Open();
                int rowsAffected = insert.ExecuteNonQuery();
                conn.Close();
                return rowsAffected;
            }
        }

        /// <summary>
        /// This will allow one to read the current setting for 
        /// lowest amount of beer in the fridge allowed before notifying.
        /// </summary>
        /// <returns>The minimum allowed amount of beers in the fridge before notifying</returns>
        public int GetFridgeMin()
        {
            const string sql = "SELECT TOP 1 minFridge FROM settings ORDER BY id DESC";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand query = new SqlCommand(sql, conn))
            {
                conn.Open();
                using (SqlDataReader result = query.ExecuteReader())
                {
                    result.Read();
                    int val = result.GetInt32(0);
                    conn.Close();
                    return val;
                }
            }
        }

        /// <summary>
        /// Get the setting for max-capacity of beers in the fridge. 
        /// This value is manually set and used to show percentage 
        /// left in the fridge.
        /// </summary>
        /// <returns>The value for max-capacity in the fridge (which is manually set)</returns>
        public int GetFridgeMax()
        {
            const string sql = "SELECT TOP 1 maxFridge FROM settings ORDER BY id DESC";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand query = new SqlCommand(sql, conn))
            {
                conn.Open();
                using (SqlDataReader result = query.ExecuteReader())
                {
                    result.Read();
                    int val = result.GetInt32(0);
                    conn.Close();
                    return val;
                }
            }
        }

        /// <summary>
        /// Get the setting for lowest allowed stock-piled beer amount,
        /// before notifying the purchase manager.
        /// </summary>
        /// <returns>The lowest allowed amount of beer on stock, before notifying</returns>
        public int GetNotifyMin()
        {
            const string sql = "SELECT TOP 1 minNotify FROM settings ORDER BY id DESC";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand query = new SqlCommand(sql, conn))
            {
                conn.Open();
                using (SqlDataReader result = query.ExecuteReader())
                {
                    result.Read();
                    int val = result.GetInt32(0);
                    conn.Close();
                    return val;
                }
            }
        }

        /// <summary>
        /// This will determine a users role. This is used in the CMS to determine once priviledges.
        /// </summary>
        /// <param name="token">Hardcoded Peper</param>
        /// <param name="id">The user id to check against</param>
        /// <returns>The role of the user</returns>
        public int GetRole(string token, string id)
        {
            int userId;
            if (token != salt || !int.TryParse(id, out userId))
                return -1;

            const string sql = "SELECT role FROM profiles WHERE id=@id";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand query = new SqlCommand(sql, conn))
            {
                query.Parameters.AddWithValue("@id", userId);
                conn.Open();
                using (SqlDataReader result = query.ExecuteReader())
                {
                    if (!result.HasRows)
                        return -1;

                    result.Read();
                    int val = result.GetInt32(0);
                    conn.Close();
                    return val;
                }
            }
        }

        public int UpdateStorage(string token, string value)
        {

            // Confirm token and check value is numeric
            int newValue;
            if (token != salt || !int.TryParse(value, out newValue))
                return -1;

            int datetime = CreateDateTime();
            int amount = int.Parse(GetFridgeValue());

            const string sql = "INSERT INTO beers (amount, total, dt) VALUES (@amount, @total, @dt)";
            using (SqlConnection conn = new SqlConnection(db))
            using (SqlCommand insert = new SqlCommand(sql, conn))
            {

                insert.Parameters.AddWithValue("@dt", datetime);
                insert.Parameters.AddWithValue("@total", newValue);
                insert.Parameters.AddWithValue("@amount", amount);

                conn.Open();
                int rowsAffected = insert.ExecuteNonQuery();
                conn.Close();
                return rowsAffected;
            }
        }
    }
}
