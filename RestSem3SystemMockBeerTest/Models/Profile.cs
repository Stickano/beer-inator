using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.InteropServices;
using System.Runtime.Serialization;
using System.Web;

namespace RestSem3SystemMockBeerTest.Models
{
    [DataContract]
    public class Profile
    {
        [DataMember]
        public string uname { get; set; }
        [DataMember]
        public string fullname { get; set; }
        [DataMember]
        public int role { get; set; }
        [DataMember]
        public string password { get; set; }
        [DataMember]
        public int id { get; set; }

        public override string ToString()
        {
            return "uname: " + uname + ", password: " + password + ", fullname: " + fullname + ", role: " + role;
        }
    }
}